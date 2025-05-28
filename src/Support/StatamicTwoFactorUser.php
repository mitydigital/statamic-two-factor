<?php

namespace MityDigital\StatamicTwoFactor\Support;

use Illuminate\Support\Facades\Cookie;
use Statamic\Facades\User;

class StatamicTwoFactorUser
{
    public function getLastChallenged(\Statamic\Contracts\Auth\User $user): ?string
    {
        $lastChallenged = Cookie::get('statamic-two-factor-last-challenged', null);

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

    public function setLastChallenged(\Statamic\Contracts\Auth\User $user): static
    {
        if (! $user) {
            return $this;
        }

        Cookie::queue('statamic-two-factor-last-challenged', encrypt(now()));

        return $this;
    }

    public function clearLastChallenged(\Statamic\Contracts\Auth\User $user): static
    {
        if (! $user) {
            return $this;
        }

        Cookie::queue('statamic-two-factor-last-challenged', null);

        return $this;
    }

    public function isTwoFactorEnforceable(\Statamic\Contracts\Auth\User $user): bool
    {
        if (! $user) {
            $user = $this->get();
        }

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
