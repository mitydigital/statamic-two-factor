<?php

namespace MityDigital\StatamicTwoFactor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MityDigital\StatamicTwoFactor\Actions\CompleteTwoFactorAuthenticationSetup;
use MityDigital\StatamicTwoFactor\Actions\ConfirmTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Actions\EnableTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use Statamic\Facades\CP\Toast;
use Statamic\Facades\User;

class TwoFactorSetupController extends BaseController
{
    public function index(Request $request, Google2FA $provider, EnableTwoFactorAuthentication $enable)
    {
        // if we have an error for the code, then disable resetting the secret
        $resetSecret = true;
        if (optional(session()->get('errors'))->first('code')) {
            // we have tried a code, but failed
            $resetSecret = false;
        }

        // enable two factor, and optionally reset the user's code
        $enable(User::current(), $resetSecret);

        // show the setup view
        return view('statamic-two-factor::setup', [
            'qr' => $provider->getQrCodeSvg(),
            'secret_key' => $provider->getSecretKey(),
        ]);
    }

    public function store(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        // confirm two factor
        $confirm(User::current(), $request->input('code', null));

        // show recovery codes
        return view('statamic-two-factor::recovery-codes', [
            'recovery_codes' => json_decode(decrypt(User::current()->two_factor_recovery_codes), true),
        ]);
    }

    public function complete(Request $request, CompleteTwoFactorAuthenticationSetup $complete)
    {
        // complete the setup
        $complete(User::current());

        Toast::success(__('statamic-two-factor::messages.setup'));

        return redirect(cp_route('index'));
    }
}
