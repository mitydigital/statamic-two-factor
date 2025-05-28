<?php

namespace MityDigital\StatamicTwoFactor\Facades;

use Illuminate\Support\Facades\Facade;
use Statamic\Contracts\Auth\User;

/**
 * @method static ?string getLastChallenged(\Statamic\Contracts\Auth\User $user)
 * @method static User get()
 * @method static static setLastChallenged(\Statamic\Contracts\Auth\User $user)
 * @method static static clearLastChallenged(\Statamic\Contracts\Auth\User $user)
 * @method static bool isTwoFactorEnforceable(\Statamic\Contracts\Auth\User $user)
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
