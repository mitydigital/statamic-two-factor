<?php

namespace MityDigital\StatamicTwoFactor\Facades;

use Illuminate\Support\Facades\Facade;
use Statamic\Contracts\Auth\User;

/**
 * @method static ?string getLastChallenged(?\Statamic\Contracts\Auth\User $user = null)
 * @method static User get()
 * @method static static setLastChallenged()
 * @method static static clearLastChallenged()
 * @method static bool isTwoFactorEnforceable()
 *
 * @see \MityDigital\StatamicTwoFactor\Support\StatamicTwoFactorUser
 */
class StatamicTwoFactorUser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'statamicTwoFactorUser';
    }
}
