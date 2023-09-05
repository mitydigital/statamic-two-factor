<?php

namespace MityDigital\StatamicTwoFactor\Facades;

use Illuminate\Support\Facades\Facade;

class StatamicTwoFactorUser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'statamicTwoFactorUser';
    }
}
