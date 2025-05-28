<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use MityDigital\StatamicTwoFactor\Actions\CompleteTwoFactorAuthenticationSetup;
use MityDigital\StatamicTwoFactor\Actions\ConfirmTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Actions\EnableTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Concerns\GetsTwoFactorRequestUser;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use Statamic\Facades\CP\Toast;
use Statamic\Facades\User;

class TwoFactorSetupController extends BaseController
{
    use GetsTwoFactorRequestUser;

    public function index(Request $request, Google2FA $provider, EnableTwoFactorAuthentication $enable)
    {
        // if we have an error for the code, then disable resetting the secret
        $resetSecret = true;
        if (optional(session()->get('errors'))->first('code')) {
            // we have tried a code, but failed
            $resetSecret = false;
        }

        $user = $this->getUserFromRequest($request);

        // enable two factor, and optionally reset the user's code
        $enable($user, $resetSecret);

        // show the setup view
        return view('statamic-two-factor::setup', [
            'cancellable' => Arr::get($user->two_factor, 'cancellable', false),
            'qr' => $provider->getQrCodeSvg($user),
            'secret_key' => $provider->getSecretKey($user),
        ]);
    }

    public function store(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $user = $this->getUserFromRequest($request);

        // confirm two factor
        $confirm($user, $request->input('code', null));

        // show recovery codes
        return view('statamic-two-factor::recovery-codes', [
            'recovery_codes' => json_decode(decrypt($user->two_factor_recovery_codes), true),
        ]);
    }

    public function complete(Request $request, CompleteTwoFactorAuthenticationSetup $complete)
    {
        $user = $this->getUserFromRequest($request);

        // complete the setup
        $complete($user);

        // authenticate the user
        Auth::guard()->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // forget the login data
        $request->session()->forget(['login.id', 'login.remember']);

        Toast::success(__('statamic-two-factor::messages.setup'));

        return redirect(cp_route('index'));
    }
}
