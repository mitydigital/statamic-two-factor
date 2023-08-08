<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MityDigital\StatamicTwoFactor\Actions\UnlockUser;
use Statamic\Facades\User;

class UserLockedController extends BaseController
{
    public function destroy(Request $request, UnlockUser $unlock)
    {
        $requestingUser = User::current();
        $user = User::find($request->user);

        // can they edit the user AND ARE NOT THEMSELVES - they can't unlock themselves?
        if (! $requestingUser->can('edit', $user) || $requestingUser->id == $user->id) {
            abort(403);
        }

        // clear all two factor states for the user
        $unlock($user);

        // success
        return [];
    }
}
