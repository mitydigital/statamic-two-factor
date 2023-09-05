<?php

use Illuminate\Support\Facades\Event;
use MityDigital\StatamicTwoFactor\Listeners\UserSavedListener;
use Statamic\Events\UserSaved;

beforeEach(function () {
    $this->user = createUser();
});

it('listens for the user saved event when creating a user', function () {
    Event::fake();

    // create a new user
    $user = createUser();

    Event::assertListening(UserSaved::class, UserSavedListener::class);
});

it('listens for the user saved event when updating a user', function () {
    Event::fake();

    // update an existing user
    $this->user->set('name', 'Johnny')->save();

    Event::assertListening(UserSaved::class, UserSavedListener::class);
});

it('listens for the user saved event when deleting a user', function () {
    Event::fake();

    // delete an existing user
    $this->user->delete();

    Event::assertListening(UserSaved::class, UserSavedListener::class);
});

it('correctly updates the "two factor" summary for the user', function () {
    // should update "locked" and "setup" based on the setup of the user
    // we'll fudge it for the test to mock the fields being set

    // delete the user to clear everything
    $this->user->delete();
    $this->user = createUser();

    expect($this->user->two_factor)
        ->toBeArray()
        ->toMatchArray([
            'locked' => false,
            'setup' => false,
        ]);

    // mark as set up
    $this->user->set('two_factor_confirmed_at', now())
        ->save();

    expect($this->user->two_factor)
        ->toBeArray()
        ->toMatchArray([
            'locked' => false,
            'setup' => true,
        ]);

    // mark as locked
    $this->user->set('two_factor_locked', now())
        ->save();

    expect($this->user->two_factor)
        ->toBeArray()
        ->toMatchArray([
            'locked' => true,
            'setup' => true,
        ]);

    // unlock
    $this->user->set('two_factor_locked', false)
        ->save();

    expect($this->user->two_factor)
        ->toBeArray()
        ->toMatchArray([
            'locked' => false,
            'setup' => true,
        ]);

    // reset
    $this->user->set('two_factor_confirmed_at', null)
        ->save();

    expect($this->user->two_factor)
        ->toBeArray()
        ->toMatchArray([
            'locked' => false,
            'setup' => false,
        ]);
});
