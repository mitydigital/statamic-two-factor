<?php

namespace MityDigital\StatamicTwoFactor\Actions;

use MityDigital\StatamicTwoFactor\Support\Google2FA;
use Statamic\Auth\User;

class EnableTwoFactorAuthentication
{
    public function __construct(private Google2FA $provider) {}

    public function __invoke(User $user, bool $resetSecret)
    {
        // update the user
        $user->remove('two_factor_confirmed_at');
        $user->remove('two_factor_completed');
        $user->set('two_factor_locked', false);
        if ($resetSecret) {
            $user->set('two_factor_secret', encrypt($this->provider->generateSecretKey()));
            app(CreateRecoveryCodes::class)($user);
        }
        $user->save();
    }
}
