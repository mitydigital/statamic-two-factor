<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use MityDigital\StatamicTwoFactor\Actions\ChallengeTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Concerns\GetsReferrerUrl;
use MityDigital\StatamicTwoFactor\Concerns\GetsTwoFactorRequestUser;

class TwoFactorChallengeController extends BaseController
{
    use GetsReferrerUrl;
    use GetsTwoFactorRequestUser;

    public function index(Request $request)
    {
        $user = $this->getUserFromRequest($request);

        // if we have a failed attempt, increase the attempts - if needed, the reload will redirect to the locked screen
        if (optional(session()->get('errors'))->first('code') || optional(session()->get('errors'))->first('recovery_code')) {
            $request->session()->put('statamic_two_factor_attempts',
                $request->session()->get('statamic_two_factor_attempts', 0) + 1);
        }

        // if we have exceeded the number of attempts, lock the account
        if ($request->session()->get('statamic_two_factor_attempts', 0) >= config('statamic-two-factor.attempts')) {
            // block the account
            $user->set('two_factor_locked', true)->save();

            // redirect to the locked view
            return redirect(cp_route('statamic-two-factor.locked'));
        }

        // if we have a referrer URL, set it
        if (! $request->wantsJson()) {
            if ($referrer = $this->getReferrerUrl($request)) {
                // if we are not null, let's set it (this way it won't overwrite on failed attempts)
                if ($referrer) {
                    $request->session()->put('statamic_two_factor_referrer', $referrer);
                }
            }
        }

        // show the challenge view
        return view('statamic-two-factor::challenge', [
            'mode' => $request->session()->get('mode', 'code'),
        ]);
    }

    public function store(Request $request, ChallengeTwoFactorAuthentication $challenge)
    {
        $user = $this->getUserFromRequest($request);

        // set the mode
        $mode = $request->get('mode', 'code');
        $request->session()->flash('mode', $mode);

        // do the challenge
        $challenge($user, $mode, $request->input($mode, null));

        // forget the attempts count
        $request->session()->forget('statamic_two_factor_attempts');

        if (! $request->user()) {
            // log them in
            Auth::guard()->login($user, $request->boolean('remember'));
            $request->session()->regenerate();
        }

        // get the redirect route, or the referrer if we set one
        $route = cp_route('index');
        if ($referrer = session()->pull('statamic_two_factor_referrer', null)) {
            $route = $referrer;
        }

        return redirect($route);
    }
}
