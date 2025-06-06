<?php

use MityDigital\StatamicTwoFactor\Http\Controllers\LockedUserController;
use Statamic\Facades\User;

beforeEach(function () {
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);
});

it('redirects to the dashboard if the user is not locked', function () {
    // not locked
    expect($this->user->two_factor_locked)->toBeFalsy();

    // expect redirect
    $this->get(action([LockedUserController::class, 'index']))
        ->assertRedirect(cp_route('index'));
});

it('logs the user out and returns the locked view when the user is locked', function () {
    // lock the user
    $this->user->set('two_factor_locked', true)->save();

    // locked, and the logged in user
    expect($this->user->two_factor_locked)
        ->toBeTrue()
        ->and(User::current()->id)->toBe($this->user->id);

    $this->get(action([LockedUserController::class, 'index']))
        ->assertViewIs('statamic-two-factor::locked');

    // should no longer be logged in
    expect(User::current())
        ->toBeNull();
});
