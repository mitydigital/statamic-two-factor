<?php

namespace MityDigital\StatamicTwoFactor\Actions;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use MityDigital\StatamicTwoFactor\Exceptions\InvalidChallengeModeException;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use MityDigital\StatamicTwoFactor\Notifications\RecoveryCodeUsedNotification;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use MityDigital\StatamicTwoFactor\Support\RecoveryCode;
use Statamic\Auth\User;

class ChallengeTwoFactorAuthentication
{
    public function __construct(private Google2FA $provider)
    {
    }

    public function __invoke(\App\Models\User|User $user, string $mode, ?string $code)
    {
        // challenge
        if (empty($user->two_factor_secret)) {
            throw ValidationException::withMessages([
                $mode => [__('statamic-two-factor::messages.two_factor_not_setup')],
            ]);
        }

        if ($mode != 'code' && $mode != 'recovery_code') {
            throw new InvalidChallengeModeException();
        }

        // call the challenge method
        // these will either succeed or throw an exception and halt execution
        $this->{Str::camel('challenge_'.$mode)}($user, $code);

        // save session
        StatamicTwoFactorUser::setLastChallenged($user);
    }

    protected function challengeCode(\App\Models\User|User $user, ?string $code): void
    {
        if (empty($code) ||
            ! $this->provider->verify(decrypt($user->two_factor_secret), $code)) {
            throw ValidationException::withMessages([
                'code' => [__('statamic-two-factor::messages.code_challenge_failed')],
            ])->redirectTo(cp_route('statamic-two-factor.challenge'));
        }
    }

    protected function challengeRecoveryCode(User $user, ?string $recovery_code): void
    {
        // must have a code
        if (! $recovery_code ||
            empty($recovery_code)) {
            throw ValidationException::withMessages([
                'recovery_code' => [__('statamic-two-factor::messages.recovery_code_challenge_failed')],
            ]);
        }

        // get the recovery codes
        $userRecoveryCodes = collect(json_decode(decrypt($user->two_factor_recovery_codes), true));

        // find the recovery code
        $foundRecoveryCode = $userRecoveryCodes->first(fn ($code) => hash_equals($code, $recovery_code) ? $code : null);

        // are we valid?
        if (! $foundRecoveryCode) {
            throw ValidationException::withMessages([
                'recovery_code' => [__('statamic-two-factor::messages.recovery_code_challenge_failed')],
            ]);
        }

        // create a new recovery code
        $userRecoveryCodes = $userRecoveryCodes->replace([
            $userRecoveryCodes->search($foundRecoveryCode) => RecoveryCode::generate(),
        ]);

        // update the user's codes
        $user->set('two_factor_recovery_codes', encrypt(json_encode($userRecoveryCodes)));
        $user->save();

        // notify the user
        $user->notify(new RecoveryCodeUsedNotification());
    }
}
