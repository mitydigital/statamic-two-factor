<?php

use MityDigital\StatamicTwoFactor\Actions\CompleteTwoFactorAuthenticationSetup;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    // create a user
    $this->user = createUser();
    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(CompleteTwoFactorAuthenticationSetup::class);
});

it('correctly updates the user as having two factor enabled', function () {
    // freeze time
    testTime()->freeze();

    // should be null
    expect($this->user->two_factor_completed)->toBeNull();

    // action
    $this->action->__invoke($this->user);

    // should not null
    expect($this->user->two_factor_completed)
        ->not()->toBeNull()
        ->toEqual(now());
});
