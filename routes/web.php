<?php

use MityDigital\StatamicTwoFactor\Http\Controllers\LockedUserController;
use MityDigital\StatamicTwoFactor\Http\Controllers\TwoFactorChallengeController;
use MityDigital\StatamicTwoFactor\Http\Controllers\TwoFactorSetupController;

if (config('statamic-two-factor.enabled', false) && config('statamic.cp.enabled', false)) {

    Route::prefix(config('statamic.cp.route', 'cp'))

        ->group(function () {
            Route::get('auth/two-factor/setup', [TwoFactorSetupController::class, 'index'])
                ->name('statamic.cp.statamic-two-factor.setup');

            Route::post('auth/two-factor/setup', [TwoFactorSetupController::class, 'store'])
                ->name('statamic.cp.statamic-two-factor.confirm');

            Route::post('auth/two-factor/complete', [TwoFactorSetupController::class, 'complete'])
                ->name('statamic.cp.statamic-two-factor.complete');

            Route::get('auth/two-factor/challenge', [TwoFactorChallengeController::class, 'index'])
                ->name('statamic.cp.statamic-two-factor.challenge');

            Route::post('auth/two-factor/challenge', [TwoFactorChallengeController::class, 'store'])
                ->name('statamic.cp.statamic-two-factor.challenge.attempt');

            Route::get('auth/two-factor/locked', [LockedUserController::class, 'index'])
                ->name('statamic.cp.statamic-two-factor.locked');
        });
}
