<?php

use MityDigital\StatamicTwoFactor\Actions\DisableTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserResetController;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use Statamic\Facades\Role;

beforeEach(function () {
    $this->admin = createUserWithTwoFactor();
    $this->admin->set('super', true); // make a super user to get CP access
    $this->actingAs($this->admin);
    $this->withoutExceptionHandling();
    $this->withoutMiddleware(EnforceTwoFactor::class);
});

it('uses the disable two factor authentication action', function () {
    trackActions([
        DisableTwoFactorAuthentication::class => 1,
    ]);

    $otherUser = createUserWithTwoFactor();
    $this->delete(action([UserResetController::class, 'destroy'], [
        'user' => $otherUser->id,
    ]))
        ->assertStatus(200)
        ->assertJson(['redirect' => null]);
});

it('provides the logout route as a redirect if reset is self and enforceable', function () {

    expect(StatamicTwoFactorUser::isTwoFactorEnforceable($this->admin))->toBeTrue();

    $this->delete(action([UserResetController::class, 'destroy'], [
        'user' => $this->admin->id,
    ]))
        ->assertStatus(200)
        ->assertJson(['redirect' => cp_route('logout')]);
});

it('does not provide the logout route as a redirect if reset is self and not enforceable', function () {
    // no roles enforced
    config()->set('statamic-two-factor.enforced_roles', []);

    // create special user
    $user = createUser(false);

    $role = Role::make('enforceable_role')
        ->addPermission('access cp')
        ->addPermission('view users')
        ->addPermission('edit users')
        ->save();

    $user->assignRole($role)->save();

    expect(StatamicTwoFactorUser::isTwoFactorEnforceable($user))->toBeFalse();

    $this->delete(action([UserResetController::class, 'destroy'], [
        'user' => $user->id,
    ]))
        ->assertStatus(200)
        ->assertJson(['redirect' => null]);
});
