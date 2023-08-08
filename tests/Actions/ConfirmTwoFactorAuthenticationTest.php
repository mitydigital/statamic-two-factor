<?php

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use MityDigital\StatamicTwoFactor\Actions\ConfirmTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use MityDigital\StatamicTwoFactor\Support\RecoveryCode;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    // get the provider
    $this->provider = app(Google2FA::class);

    // create a user
    $this->user = createUser();

    // partially set up two factor
    $this->user->set('two_factor_confirmed_at', null);
    $this->user->set('two_factor_completed', null);
    $this->user->set('two_factor_locked', false);
    $this->user->set('two_factor_secret', encrypt($this->provider->generateSecretKey()));
    $this->user->set('two_factor_recovery_codes', encrypt(json_encode(Collection::times(8, function () {
        return RecoveryCode::generate();
    })->all())));
    $this->user->save();

    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(ConfirmTwoFactorAuthentication::class);
});

it('throws a validation exception when a no one time code is present', function () {
    $this->action->__invoke($this->user, null);
})->throws(ValidationException::class, 'The provided two factor authentication code was invalid.');

it('throws a validation exception when an invalid one time code is present', function () {
    // get a one-time code (so we can make sure we have a wrong one in the test)
    $code = getCode();

    // if our actual code is 111111, then output 222222
    if ($code === '111111') {
        $code = '222222';
    } else {
        $code = '111111';
    }

    // try to challenge with no code passed
    $this->action->__invoke($this->user, $code);
})->throws(ValidationException::class, 'The provided two factor authentication code was invalid.');

it('correctly confirms two factor authentication', function () {
    // freeze time
    testTime()->freeze();

    // get a code
    $code = getCode();

    // expect no session var, and that the user is not set up
    expect(session()->get('statamic_two_factor'))
        ->toBeNull()
        ->and($this->user->two_factor_confirmed_at)
        ->toBeNull();

    // confirm two factor
    $this->action->__invoke($this->user, $code);

    // expect the user is now marked as confirmed
    expect($this->user->two_factor_confirmed_at)
        ->not()->toBeNull()
        ->toEqual(now());

    // expect new session var
    $session = decrypt(session()->get('statamic_two_factor'));
    expect($session)
        ->not()->toBeNull()
        ->toEqual(now());
});
