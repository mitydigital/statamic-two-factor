<?php

use MityDigital\StatamicTwoFactor\Http\Controllers\LockedUserController;
use MityDigital\StatamicTwoFactor\Http\Controllers\TwoFactorChallengeController;
use MityDigital\StatamicTwoFactor\Http\Controllers\TwoFactorSetupController;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserLockedController;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserRecoveryCodesController;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserResetController;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use MityDigital\StatamicTwoFactor\Http\Middleware\SetupAvailableWhenTwoFactorSetupIncomplete;

if (config('statamic-two-factor.enabled', false)) {
    Route::withoutMiddleware([
        EnforceTwoFactor::class,
    ])->middleware([
        SetupAvailableWhenTwoFactorSetupIncomplete::class,
    ])->group(function () {

        Route::get('auth/two-factor/setup', [TwoFactorSetupController::class, 'index'])
            ->name('statamic-two-factor.setup');

        Route::post('auth/two-factor/setup', [TwoFactorSetupController::class, 'store'])
            ->name('statamic-two-factor.confirm');

        Route::post('auth/two-factor/complete', [TwoFactorSetupController::class, 'complete'])
            ->name('statamic-two-factor.complete');
    });

    Route::withoutMiddleware([
        EnforceTwoFactor::class,
    ])->group(function () {

        Route::get('auth/two-factor/challenge', [TwoFactorChallengeController::class, 'index'])
            ->name('statamic-two-factor.challenge');

        Route::post('auth/two-factor/challenge', [TwoFactorChallengeController::class, 'store'])
            ->name('statamic-two-factor.challenge.attempt');

        Route::get('auth/two-factor/locked', [LockedUserController::class, 'index'])
            ->name('statamic-two-factor.locked');
    });

    Route::get('users/{user}/two-factor/recovery-codes', [UserRecoveryCodesController::class, 'show'])
        ->name('statamic-two-factor.user.recovery-codes.show');
    Route::post('users/{user}/two-factor/recovery-codes', [UserRecoveryCodesController::class, 'store'])
        ->name('statamic-two-factor.user.recovery-codes.generate');

    Route::delete('users/{user}/two-factor/lock', [UserLockedController::class, 'destroy'])
        ->name('statamic-two-factor.user.unlock');

    Route::delete('users/{user}/two-factor', [UserResetController::class, 'destroy'])
        ->name('statamic-two-factor.user.reset');
}
