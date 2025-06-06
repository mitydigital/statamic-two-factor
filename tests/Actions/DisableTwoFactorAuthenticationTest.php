<?php

use MityDigital\StatamicTwoFactor\Actions\DisableTwoFactorAuthentication;

beforeEach(function () {
    // create a user
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(DisableTwoFactorAuthentication::class);
});

it('correctly removes two factor authentication from the user', function () {
    // should NOT be null
    expect($this->user)
        ->two_factor_confirmed_at->not()->toBeNull()
        ->two_factor_completed->not()->toBeNull()
        ->two_factor_recovery_codes->not()->toBeNull()
        ->two_factor_secret->not()->toBeNull()
        ->two_factor_locked->not()->toBeNull();

    // action
    $this->action->__invoke($this->user);

    // should be null
    expect($this->user)
        ->two_factor_confirmed_at->toBe('')
        ->two_factor_completed->toBe('')
        ->two_factor_recovery_codes->toBe('')
        ->two_factor_secret->toBe('')
        ->two_factor_locked->toBeFalse();
});
