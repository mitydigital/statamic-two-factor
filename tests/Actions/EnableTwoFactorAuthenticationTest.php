<?php

use MityDigital\StatamicTwoFactor\Actions\EnableTwoFactorAuthentication;

use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    // create a user
    $this->user = createUser();
    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(EnableTwoFactorAuthentication::class);
});

it('correctly updates the user as partially set up', function () {
    // freeze time
    testTime()->freeze();

    // should be null
    expect($this->user)
        ->two_factor_completed->toBeNull()
        ->two_factor_confirmed_at->toBeNull()
        ->two_factor_recovery_codes->toBeNull()
        ->two_factor_secret->toBeNull();

    // action
    $this->action->__invoke($this->user, true);

    // should not null
    expect($this->user)
        ->two_factor_completed->toBeNull()
        ->two_factor_confirmed_at->toBeNull()
        ->two_factor_recovery_codes->not()->toBeNull()
        ->two_factor_secret->not()->toBeNull();

    // store existing codes and secret
    $recoveryCodes = $this->user->two_factor_recovery_codes;
    $secret = $this->user->two_factor_secret;

    // re-action, WITHOUT resetting the secret
    $this->action->__invoke($this->user, false);

    expect($this->user)
        ->two_factor_recovery_codes->toBe($recoveryCodes)
        ->two_factor_secret->toBe($secret);

    // re-action, WITH resetting the secret
    $this->action->__invoke($this->user, true);

    expect($this->user)
        ->two_factor_recovery_codes
        ->not()->toBeNull()
        ->not()->toBe($recoveryCodes)
        ->two_factor_secret
        ->not()->toBeNull()
        ->not()->toBe($secret);
});
