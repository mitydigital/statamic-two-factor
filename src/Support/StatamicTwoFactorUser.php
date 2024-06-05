<?php

namespace MityDigital\StatamicTwoFactor\Support;

use Illuminate\Support\Facades\Config;
use Statamic\Facades\User;

class StatamicTwoFactorUser
{
    public function getLastChallenged(): ?string
    {
        // get the user
        $user = $this->get();

        $lastChallenged = null;

        // no user, return
        if (! $user) {
            return $lastChallenged;
        }

        // are we using eloquent or flat file
        if (Config::get('statamic.users.repository') === 'eloquent') {
            $lastChallenged = $user->get('two_factor_last_challenged', null);
        } else {
            $lastChallenged = $user->getMeta('statamic_two_factor', null);
        }

        // if we have a challenge, decrypt it
        if ($lastChallenged) {
            $lastChallenged = decrypt($lastChallenged);
        }

        return $lastChallenged;
    }

    public function get(): ?\Statamic\Contracts\Auth\User
    {
        return User::current();
    }

    public function setLastChallenged(): static
    {
        // get the user
        $user = $this->get();

        if (! $user) {
            return $this;
        }

        // are we using eloquent or flat file
        if (Config::get('statamic.users.repository') === 'eloquent') {
            $user->set('two_factor_last_challenged', encrypt(now()));
            $user->save();
        } else {
            $user->setMeta('statamic_two_factor', encrypt(now()));
        }

        return $this;
    }

    public function clearLastChallenged(): static
    {
        // get the user
        $user = $this->get();

        if (! $user) {
            return $this;
        }

        // are we using eloquent or flat file
        if (Config::get('statamic.users.repository') === 'eloquent') {
            $user->set('two_factor_last_challenged', null);
            $user->save();
        } else {
            $user->setMeta('statamic_two_factor', null);
        }

        return $this;
    }

    public function isTwoFactorEnforceable(): bool
    {
        $user = static::get();

        // no user - so not enforceable
        if (! $user) {
            return false;
        }

        // super admin are always enforced

        if ($user->isSuper()) {
            return true;
        }

        // get configured enforced roles
        $enforcedRoles = config('statamic-two-factor.enforced_roles', null);

        // null means all roles are enforced
        if ($enforcedRoles === null) {
            return true;
        }

        // if an array of roles check if the user contains ANY of them
        if (is_array($enforcedRoles)) {
            foreach ($enforcedRoles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }

        return false; // this far, not enforced
    }
}
