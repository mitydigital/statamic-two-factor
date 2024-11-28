<?php

namespace MityDigital\StatamicTwoFactor\Actions;

use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use Statamic\Auth\User;

class DisableTwoFactorAuthentication
{
    public function __invoke(User $user)
    {
        // update the user
        $user->set('two_factor_confirmed_at', null);
        $user->set('two_factor_completed', null);
        $user->set('two_factor_secret', null);
        $user->set('two_factor_recovery_codes', null);
        $user->set('two_factor_locked', false);
        $user->save();

        // remove the last challenged data
        StatamicTwoFactorUser::clearLastChallenged($user);
    }
}
