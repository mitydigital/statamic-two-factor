<?php

namespace MityDigital\StatamicTwoFactor\Concerns;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait GetsTwoFactorRequestUser
{
    protected function getUserFromRequest(Request $request)
    {
        if (
            ! session()->has('login.id')
            || ! $user = Auth::guard()->getProvider()->retrieveById(session()->get('login.id'))
        ) {
            throw new HttpResponseException(redirect()->route('statamic.cp.login'));
        }

        return $user;
    }
}
