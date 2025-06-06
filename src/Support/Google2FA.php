<?php

namespace MityDigital\StatamicTwoFactor\Support;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Foundation\Auth\User;
use MityDigital\StatamicTwoFactor\Exceptions\TwoFactorNotSetUpException;

class Google2FA
{
    private \PragmaRX\Google2FA\Google2FA $provider;

    private ?string $secret_key = null;

    public function __construct()
    {
        $this->provider = app(\PragmaRX\Google2FA\Google2FA::class);
    }

    public function getQrCodeSvg(\Statamic\Contracts\Auth\User|User $user)
    {
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(200, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($this->getQrCode($user));

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    public function getQrCode(\Statamic\Contracts\Auth\User|User $user)
    {
        return $this->provider->getQRCodeUrl(
            config('app.name'),
            $user->email(),
            $this->getSecretKey($user)
        );
    }

    public function getSecretKey(\Statamic\Contracts\Auth\User|User $user)
    {
        $secret = $user->two_factor_secret;
        if (! $secret) {
            throw new TwoFactorNotSetUpException();
        }

        return decrypt($secret);
    }

    public function generateSecretKey()
    {
        return $this->provider->generateSecretKey();
    }

    public function verify($secret, $code)
    {
        $timestamp = $this->provider->verifyKey($secret, $code);

        if ($timestamp !== false) {
            return true;
        }

        return false;
    }
}
