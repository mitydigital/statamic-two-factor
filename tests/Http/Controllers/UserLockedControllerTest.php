<?php

use MityDigital\StatamicTwoFactor\Actions\UnlockUser;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserLockedController;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;

beforeEach(function () {
    $this->admin = createUserWithTwoFactor();
    $this->admin->set('super', true); // make a super user to get CP access
    $this->actingAs($this->admin);

    $this->withoutMiddleware(EnforceTwoFactor::class);
});

it('cannot be performed on yourself', function () {
    $this->delete(action([UserLockedController::class, 'destroy'], [
        'user' => $this->admin->id,
    ]))
        ->assertStatus(403);

    // try with another user
    $otherUser = createUserWithTwoFactor();
    $this->delete(action([UserLockedController::class, 'destroy'], [
        'user' => $otherUser->id,
    ]))
        ->assertStatus(200);
});

it('uses the unlock user action', function () {
    trackActions([
        UnlockUser::class => 1,
    ]);

    $otherUser = createUserWithTwoFactor();
    $this->delete(action([UserLockedController::class, 'destroy'], [
        'user' => $otherUser->id,
    ]))
        ->assertStatus(200);
});
