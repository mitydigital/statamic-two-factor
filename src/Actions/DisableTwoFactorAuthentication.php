<?php

namespace MityDigital\StatamicTwoFactor\Actions;

use Statamic\Auth\User;

class DisableTwoFactorAuthentication
{
    public function __invoke(User $user)
    {
        // update the user
        $user->remove('two_factor_confirmed_at');
        $user->remove('two_factor_completed');
        $user->remove('two_factor_secret');
        $user->remove('two_factor_recovery_codes');
        $user->set('two_factor_locked', false);
        $user->saveQuietly();
    }
}
