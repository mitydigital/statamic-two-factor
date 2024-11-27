<?php

namespace MityDigital\StatamicTwoFactor\Facades;

use Illuminate\Support\Facades\Facade;
use Statamic\Contracts\Auth\User;

/**
 * @method static ?string getLastChallenged(?\Statamic\Contracts\Auth\User $user = null)
 * @method static User get()
 * @method static static setLastChallenged(?User $user = null)
 * @method static static clearLastChallenged(?User $user = null)
 * @method static bool isTwoFactorEnforceable(?User $user = null)
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
