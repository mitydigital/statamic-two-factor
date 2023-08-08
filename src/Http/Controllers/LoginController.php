<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\User;

class LoginController extends \Statamic\Http\Controllers\CP\Auth\LoginController
{
    /*protected function attemptLogin(Request $request)
    {
        // log in
        $loggedIn = parent::attemptLogin($request);

        // if we are logged in, is the user two-factor locked?
        if ($loggedIn && config('statamic-two-factor.enabled', false)) {
            // if the user has been locked, show the locked view
            if (User::current()->two_factor_locked) {
                return redirect(cp_route('statamic-two-factor.locked'));
            }
        }

        return $loggedIn;
    }*/

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
