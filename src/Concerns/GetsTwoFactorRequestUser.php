<?php

namespace MityDigital\StatamicTwoFactor\Concerns;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Statamic\Facades\User;

trait GetsTwoFactorRequestUser
{
    protected function getUserFromRequest(Request $request)
    {
        // if we're already logged in, use that user
        if ($user = $request->user()) {
            return $user;
        }

        // if we have this id, then we're in the process of logging in
        if (
            ! session()->has('login.id')
            || ! $user = Auth::guard()->getProvider()->retrieveById(session()->get('login.id'))
        ) {
            throw new HttpResponseException(redirect()->route('statamic.cp.login'));
        }

        return User::find($user->id);
    }
}
