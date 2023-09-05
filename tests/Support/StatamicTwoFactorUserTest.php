<?php

use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;

it('gets the current user', function () {
    // null when not logged in
    expect(StatamicTwoFactorUser::get())->toBeNull();

    // create a user
    $user = createUser();

    // log in
    $this->actingAs($user);

    // get a user
    expect(StatamicTwoFactorUser::get())
        ->id->toBe($user->id);
});

it('sets, gets and clears the last challenged for the user', function () {
    // create user
    $user = createUserWithTwoFactor();
    $this->actingAs($user);

    // should be null
    expect(StatamicTwoFactorUser::getLastChallenged())->toBeNull();

    // set the challenge
    StatamicTwoFactorUser::setLastChallenged();

    // should be set
    expect(StatamicTwoFactorUser::getLastChallenged())->not()->toBeNull();

    // clear the challenge
    StatamicTwoFactorUser::clearLastChallenged();

    // should be null
    expect(StatamicTwoFactorUser::getLastChallenged())->toBeNull();
});