<?php

use Illuminate\Support\Collection;
use MityDigital\StatamicTwoFactor\Actions\CompleteTwoFactorAuthenticationSetup;
use MityDigital\StatamicTwoFactor\Actions\ConfirmTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Actions\EnableTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Http\Controllers\TwoFactorSetupController;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use MityDigital\StatamicTwoFactor\Support\RecoveryCode;
use Statamic\Facades\Role;

beforeEach(function () {
    $this->user = createUser();
    $this->user->set('super', true); // make a super user to get CP access

    session()->put([
        'login.id' => $this->user->getKey(),
        'login.remember' => false,
    ]);
});

//
// INDEX
//
it('shows the two factor setup view', function () {
    $this->get(action([TwoFactorSetupController::class, 'index']))
        ->assertViewIs('statamic-two-factor::setup');
});

it('redirects to the dashboard if the user is already set up', function () {
    $this->actingAs(createUserWithTwoFactor());
    $this->get(action([TwoFactorSetupController::class, 'index']))
        ->assertRedirect(cp_route('index'));
});

it('uses the enable two factor setup action', function () {
    trackActions([
        EnableTwoFactorAuthentication::class => 1,
    ]);

    $this->get(action([TwoFactorSetupController::class, 'index']))
        ->assertViewHas('qr')
        ->assertViewHas('secret_key');
});

it('has the correct cancellable property', function () {
    $this->get(action([TwoFactorSetupController::class, 'index']))
        ->assertViewHas('cancellable', false);

    // no roles enforced
    config()->set('statamic-two-factor.enforced_roles', []);

    $user = createUser(false);
    session()->put([
        'login.id' => $user->getKey(),
        'login.remember' => false,
    ]);

    $role = Role::make('enforceable_role')
        ->addPermission('access cp')
        ->save();

    $user->assignRole($role)->save();

    $this->get(action([TwoFactorSetupController::class, 'index']))
        ->assertViewHas('cancellable', true);

});

//
// STORE
//
it('uses the confirm two factor setup action, and shows the recovery codes', function () {
    trackActions([
        ConfirmTwoFactorAuthentication::class => 1,
    ]);

    // do the "setup" bit
    $this->user->set('two_factor_secret', encrypt(app(Google2FA::class)->generateSecretKey()));
    $this->user->set('two_factor_recovery_codes', encrypt(json_encode(Collection::times(8, function () {
        return RecoveryCode::generate();
    }))));

    $this->post(action([TwoFactorSetupController::class, 'store']), [
        'code' => getCode($this->user),
    ])
        ->assertViewIs('statamic-two-factor::recovery-codes')
        ->assertViewHas('recovery_codes');
});

//
// COMPLETE
//
it('uses the complete two factor action, and redirects', function () {
    trackActions([
        CompleteTwoFactorAuthenticationSetup::class => 1,
    ]);

    $this->post(action([TwoFactorSetupController::class, 'complete']))
        ->assertRedirect(cp_route('index'));
});
