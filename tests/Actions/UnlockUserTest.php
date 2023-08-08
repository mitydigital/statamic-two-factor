<?php

use MityDigital\StatamicTwoFactor\Actions\UnlockUser;

beforeEach(function () {
    // create a user
    $this->user = createUserWithTwoFactor();
    $this->user->set('two_factor_locked', true)->save();
    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(UnlockUser::class);
});

it('correctly removes the locked flag from the user', function () {
    // should be true
    expect($this->user)
        ->two_factor_locked->toBeTrue();

    // action
    $this->action->__invoke($this->user);

    // should be null
    expect($this->user)
        ->two_factor_locked->toBeFalse();
});
