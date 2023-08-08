<?php

use MityDigital\StatamicTwoFactor\Actions\DisableTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserResetController;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;

beforeEach(function () {
    $this->admin = createUserWithTwoFactor();
    $this->admin->set('super', true); // make a super user to get CP access
    $this->actingAs($this->admin);
    $this->withoutExceptionHandling();
    $this->withoutMiddleware(EnforceTwoFactor::class);
});

it('uses the disable two factor authentication action', function () {
    trackActions([
        DisableTwoFactorAuthentication::class => 1,
    ]);

    $otherUser = createUserWithTwoFactor();
    $this->delete(action([UserResetController::class, 'destroy'], [
        'user' => $otherUser->id,
    ]))
        ->assertStatus(200)
        ->assertJson(['redirect' => null]);
});

it('provides the logout route as a redirect if reset is self', function () {
    $this->delete(action([UserResetController::class, 'destroy'], [
        'user' => $this->admin->id,
    ]))
        ->assertStatus(200)
        ->assertJson(['redirect' => cp_route('logout')]);
});
