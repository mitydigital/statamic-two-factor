<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use Statamic\Contracts\Auth\User;

class LoginController extends \Statamic\Http\Controllers\CP\Auth\LoginController
{
    public function logout(Request $request)
    {
        // remove the last challenged
        StatamicTwoFactorUser::clearLastChallenged($request->user());

        // log out
        return parent::logout($request);
    }

    public function login(Request $request)
    {
        if (config('statamic-two-factor.enabled', false)) {
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            $user = $this->validateCredentials($request);

            if ($user->two_factor_locked) {
                return redirect(cp_route('statamic-two-factor.locked'));
            }

            if (StatamicTwoFactorUser::isTwoFactorEnforceable($user)) {
                $request->session()->put([
                    'login.id' => $user->getKey(),
                    'login.remember' => $request->boolean('remember'),
                ]);

                if ($user->two_factor_completed) {
                    return redirect(cp_route('statamic-two-factor.challenge'));
                } else {
                    return redirect(cp_route('statamic-two-factor.setup'));
                }
            }

            $this->authenticate($request, $user);

            return $this->authenticated($request, $this->guard()->user());
        } else {
            return parent::login($request);
        }

    }

    protected function validateCredentials($request)
    {
        $provider = $this->guard()->getProvider();

        $user = tap($provider->retrieveByCredentials($request->only($this->username(), 'password')), function ($user) use ($provider, $request) {
            if (! $user || ! $provider->validateCredentials($user, ['password' => $request->password])) {
                $this->failAuthentication($request, $user);
            }
        });

        return \Statamic\Facades\User::find($user->id);
    }

    protected function failAuthentication(Request $request, User $user)
    {
        $this->incrementLoginAttempts($request);
        $this->sendFailedLoginResponse($request);
    }
}
