<?php

namespace MityDigital\StatamicTwoFactor\Actions;

use Statamic\Auth\User;

class CompleteTwoFactorAuthenticationSetup
{
    public function __invoke(User $user)
    {
        // update the user
        $user->set('two_factor_completed', now());
        $user->save();
    }
}
