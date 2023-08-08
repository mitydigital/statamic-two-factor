<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MityDigital\StatamicTwoFactor\Actions\DisableTwoFactorAuthentication;
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

        // success
        return [
            // if the requesting user is the user being actioned, log the user out
            'redirect' => $user->id === $requestingUser->id ? cp_route('logout') : null,
        ];
    }
}
