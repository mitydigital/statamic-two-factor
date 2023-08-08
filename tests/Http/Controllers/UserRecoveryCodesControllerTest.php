<?php

use MityDigital\StatamicTwoFactor\Actions\CreateRecoveryCodes;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserRecoveryCodesController;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;

beforeEach(function () {
    $this->admin = createUserWithTwoFactor();
    $this->admin->set('super', true); // make a super user to get CP access
    $this->actingAs($this->admin);

    $this->withoutMiddleware(EnforceTwoFactor::class);
});

//
// SHOW
//
it('returns a 403 when trying to see codes for another user', function () {
    $this->get(action([UserRecoveryCodesController::class, 'show'], [
        'user' => createUser()->id,
    ]))
        ->assertStatus(403);
});

it('shows the recovery codes for the logged in user', function () {
    // works on yourself only
    $this->get(action([UserRecoveryCodesController::class, 'show'], [
        'user' => $this->admin->id,
    ]))
        ->assertStatus(200)
        ->assertJson([
            'recovery_codes' => json_decode(decrypt($this->admin->two_factor_recovery_codes), true),
        ]);
});

//
// STORE
//
it('returns a 403 when trying to update codes for another user', function () {
    $this->post(action([UserRecoveryCodesController::class, 'store'], [
        'user' => createUser()->id,
    ]))
        ->assertStatus(403);
});

it('uses the create recovery codes action', function () {
    trackActions([
        CreateRecoveryCodes::class => 1,
    ]);

    // works on yourself only
    $this->post(action([UserRecoveryCodesController::class, 'store'], [
        'user' => $this->admin->id,
    ]))
        ->assertStatus(200)
        ->assertJson([
            'recovery_codes' => json_decode(decrypt($this->admin->two_factor_recovery_codes), true),
        ]);
});
