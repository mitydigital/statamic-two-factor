<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MityDigital\StatamicTwoFactor\Actions\DisableTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use Statamic\Facades\User;

class UserResetController extends BaseController
{
    public function destroy(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $requestingUser = User::current();
        $user = User::find($request->user);

        // can they edit the user (or themselves)?
        if (! $requestingUser->can('edit', $user)) {
            abort(403);
        }

        // disable two factor
        $disable($user);

        // redirect
        // if two factor is enforcable, and the same user, log them out
        $redirect = null;
        if ($user->id === $requestingUser->id && StatamicTwoFactorUser::isTwoFactorEnforceable()) {
            $redirect = cp_route('logout');
        }

        // success
        return [
            'redirect' => $redirect,
        ];
    }
}
