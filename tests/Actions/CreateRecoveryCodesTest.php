<?php

use Illuminate\Support\Str;
use MityDigital\StatamicTwoFactor\Actions\CreateRecoveryCodes;

beforeEach(function () {
    // create a user
    $this->user = createUser();
    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(CreateRecoveryCodes::class);
});

it('correctly creates 8 recovery codes for a user', function () {
    // should be true
    expect($this->user)
        ->two_factor_recovery_codes->toBeNull();

    // action
    $this->action->__invoke($this->user);

    // should be null
    expect($this->user->two_factor_recovery_codes)
        ->not()->toBeNull()
        ->toBeString();

    $codes = decrypt($this->user->two_factor_recovery_codes);
    expect(Str::isJson($codes))->toBeTrue();

    $codes = json_decode($codes, true);
    expect($codes)->toBeArray()
        ->toHaveCount(8);
});
