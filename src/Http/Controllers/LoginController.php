<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use Statamic\Facades\User;

class LoginController extends \Statamic\Http\Controllers\CP\Auth\LoginController
{
    public function logout(Request $request)
    {
        // remove the last challenged
        StatamicTwoFactorUser::clearLastChallenged();

        // log out
        return parent::logout($request);
    }

    protected function authenticated(Request $request, $user)
    {
        if (config('statamic-two-factor.enabled', false)) {
            // if the user has been locked, show the locked view
            if (User::current()->two_factor_locked) {
                return redirect(cp_route('statamic-two-factor.locked'));
            }
        }

        return parent::authenticated($request, $user);
    }
}
