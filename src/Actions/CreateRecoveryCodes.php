<?php

namespace MityDigital\StatamicTwoFactor\Actions;

use Illuminate\Support\Collection;
use MityDigital\StatamicTwoFactor\Support\RecoveryCode;
use Statamic\Auth\User;

class CreateRecoveryCodes
{
    public function __invoke(User $user)
    {
        // create codes, and update the user
        $recoveryCodes = Collection::times(8, function () {
            return RecoveryCode::generate();
        })->all();
        $user->set('two_factor_recovery_codes', encrypt(json_encode($recoveryCodes)));
        $user->save();
    }
}
