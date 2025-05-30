<?php

use MityDigital\StatamicTwoFactor\Http\Controllers\UserLockedController;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserRecoveryCodesController;
use MityDigital\StatamicTwoFactor\Http\Controllers\UserResetController;

if (config('statamic-two-factor.enabled', false)) {
    Route::get('users/{user}/two-factor/recovery-codes', [UserRecoveryCodesController::class, 'show'])
        ->name('statamic-two-factor.user.recovery-codes.show');
    Route::post('users/{user}/two-factor/recovery-codes', [UserRecoveryCodesController::class, 'store'])
        ->name('statamic-two-factor.user.recovery-codes.generate');

    Route::delete('users/{user}/two-factor/lock', [UserLockedController::class, 'destroy'])
        ->name('statamic-two-factor.user.unlock');

    Route::delete('users/{user}/two-factor', [UserResetController::class, 'destroy'])
        ->name('statamic-two-factor.user.reset');
}
