<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use MityDigital\StatamicTwoFactor\Concerns\GetsTwoFactorRequestUser;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;

class LockedUserController extends BaseController
{
    use GetsTwoFactorRequestUser;

    public function index(Request $request)
    {
        // This is in a separate controller because the route SHOULD be visible while you are logged in,
        // but exceeding your limit, as this controller will log you out - so if you refresh, you're back
        // at the login view.
        //
        // This means it works from the "challenge" view within the CP, as well as the initial setup stage

        $user = $request->user();

        if (! $user) {
            $user = $this->getUserFromRequest($request);
        }

        if (! $user) {
            return redirect(cp_route('login'));
        }

        if (! $user->two_factor_locked) {
            return redirect(cp_route('index'));
        }

        StatamicTwoFactorUser::clearLastChallenged($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::guard()->logout();

        // show the lock view
        return view('statamic-two-factor::locked');
    }
}
