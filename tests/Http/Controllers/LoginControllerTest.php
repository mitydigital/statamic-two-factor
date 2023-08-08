<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    // create the user, and lock them
    $this->user = createUserWithTwoFactor();
    $this->user->set('two_factor_locked', true);
    $this->user->save();

    $this->withoutMiddleware(VerifyCsrfToken::class);
});

it('shows the locked view when the user has logged in, and they are locked', function () {

    $this->post(URL::to('cp/auth/login'), [
        'email' => $this->user->email(),
        'password' => 'secret',
    ])
        ->assertRedirect(cp_route('statamic-two-factor.locked'));
});

it('allows a non-locked user to log in', function () {

    // unlock the user
    $this->user->set('two_factor_locked', false)->save();

    $this->post(URL::to('cp/auth/login'), [
        'email' => $this->user->email(),
        'password' => 'secret',
    ])
        ->assertRedirect(cp_route('index'));
});

it('does not show the locked view when two factor as an addon is disabled', function () {

    config()->set('statamic-two-factor.enabled', false);

    expect($this->user->two_factor_locked)->toBeTrue(); // just make sure we are locked

    $this->post(URL::to('cp/auth/login'), [
        'email' => $this->user->email(),
        'password' => 'secret',
    ])
        ->assertRedirect(cp_route('index'));
});
