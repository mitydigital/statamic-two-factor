<?php

namespace MityDigital\StatamicTwoFactor\Listeners;

class UserSavedListener
{
    /**
     * The purpose of this listener is to create a "summary" of the saved user  that is used in Statamic's CP for the
     * index view of users. That's it, just making it easier for use later by the TwoFactorIndex fieldtype.
     */
    public function handle($event): void
    {
        // get the user
        $user = $event->user;

        // update the user's two factor setup and locked status
        $status = [
            'locked' => $user->two_factor_locked ? true : false,
            'setup' => $user->two_factor_confirmed_at ? true : false,
        ];

        // update the user's status fields, and quietly save (shhhhh!)
        $user->set(config('statamic-two-factor.blueprint', 'two_factor'), $status)
            ->saveQuietly();
    }
}
