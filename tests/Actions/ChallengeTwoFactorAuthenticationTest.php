<?php

use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use MityDigital\StatamicTwoFactor\Actions\ChallengeTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Exceptions\InvalidChallengeModeException;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use MityDigital\StatamicTwoFactor\Notifications\RecoveryCodeUsedNotification;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    Notification::fake();

    // create a user
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);

    // get the action
    $this->action = app()->make(ChallengeTwoFactorAuthentication::class);
});

it('throws an exception when two factor is not enabled', function () {
    // create a non-two factor user
    $user = createUser();
    $this->actingAs($user);

    $this->action->__invoke($user, '', '');
})->throws(ValidationException::class);

it('throws an invalid challenge mode exception when an invalid mode is presented', function () {
    $this->action->__invoke($this->user, '', '');
})->throws(InvalidChallengeModeException::class);

//
// ONE TIME CODES
//

it('throws a validation exception when a no one time code is present', function () {
    $this->action->__invoke($this->user, 'code', null);
})->throws(ValidationException::class, 'The provided two factor authentication code was invalid.');

it('throws a validation exception when an invalid one time code is present', function () {
    $provider = app(Google2FA::class);

    // get a one-time code (so we can make sure we have a wrong one in the test)
    $code = getCode();

    // if our actual code is 111111, then output 222222
    if ($code === '111111') {
        $code = '222222';
    } else {
        $code = '111111';
    }

    // try to challenge with no code passed
    $this->action->__invoke($this->user, 'code', $code);
})->throws(ValidationException::class, 'The provided two factor authentication code was invalid.');

it('correctly accepts a one time code challenge', function () {
    // freeze time
    testTime()->freeze();

    // get a code
    $code = getCode();

    // expect no session var
    expect(StatamicTwoFactorUser::getLastChallenged($this->user))
        ->toBeNull();

    $this->action->__invoke($this->user, 'code', $code);

    // expect new last challenged var
    $lastChallenged = StatamicTwoFactorUser::getLastChallenged($this->user);
    expect($lastChallenged)
        ->not()->toBeNull()
        ->toEqual(now());
});

//
// RECOVERY CODES
//
it('throws a validation exception when no recovery code is presented', function () {
    $this->action->__invoke($this->user, 'recovery_code', null);
})->throws(ValidationException::class, 'The provided two factor authentication code was invalid.');

it('throws a validation exception when an invalid recovery code is presented', function () {
    $this->action->__invoke($this->user, 'recovery_code', 'invalid-code');
})->throws(ValidationException::class, 'The provided two factor authentication code was invalid.');

it('correctly accepts a recovery code challenge', function () {
    // freeze time
    testTime()->freeze();

    // expect no session var
    expect(StatamicTwoFactorUser::getLastChallenged($this->user))
        ->toBeNull();

    // get a recovery code from the user
    $userRecoveryCode = collect(json_decode(decrypt($this->user->two_factor_recovery_codes), true))->first();

    // do it
    $this->action->__invoke($this->user, 'recovery_code', $userRecoveryCode);

    // expect new last challenged var
    $lastChallenged = StatamicTwoFactorUser::getLastChallenged($this->user);
    expect($lastChallenged)
        ->not()->toBeNull()
        ->toEqual(now());
});

it('removes and replaces the used recovery code on a successful usage', function () {
    // get the user codes, and save for later
    $userRecoveryCodes = collect(json_decode(decrypt($this->user->two_factor_recovery_codes), true));
    $beforeRecoveryCodes = $userRecoveryCodes->toArray();

    // get a recovery code
    $userRecoveryCode = $userRecoveryCodes->slice(3, 1)->first(); // get a code in the middle of the pack

    // do it
    $this->action->__invoke($this->user, 'recovery_code', $userRecoveryCode);

    // get the new recovery codes
    $newUserRecoveryCodes = collect(json_decode(decrypt($this->user->two_factor_recovery_codes), true));

    // still have 8 codes, but removed that one
    expect($newUserRecoveryCodes->toArray())
        ->toHaveCount(8)
        ->not()->toContain($userRecoveryCode)
        ->not()->toMatchArray($beforeRecoveryCodes);

    // others remain
    $userRecoveryCodes
        ->filter(fn($code) => $code != $userRecoveryCode)
        ->each(fn($code) => expect($newUserRecoveryCodes)->toContain($code));
});

it('sends the recovery code used notification when a recovery code is successfully used', function () {
    // get the user codes, and save for later
    $userRecoveryCodes = collect(json_decode(decrypt($this->user->two_factor_recovery_codes), true));

    // get a recovery code
    $userRecoveryCode = $userRecoveryCodes->slice(3, 1)->first(); // get a code in the middle of the pack

    // do it
    $this->action->__invoke($this->user, 'recovery_code', $userRecoveryCode);

    // ensure the user received a notification
    Notification::assertSentTo($this->user, RecoveryCodeUsedNotification::class);
});
