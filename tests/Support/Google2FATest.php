<?php

use MityDigital\StatamicTwoFactor\Exceptions\TwoFactorNotSetUpException;
use MityDigital\StatamicTwoFactor\Support\Google2FA;

beforeEach(function () {
    $this->provider = app(Google2FA::class);
    $this->user = createUser();
    $this->actingAs($this->user);
});

it('can generate a secret key', function () {
    // generate and test the key
    $key1 = $this->provider->generateSecretKey();

    expect($key1)
        ->not()->toBeNull()
        ->toBeString();

    // expect a second key to be different
    $key2 = $this->provider->generateSecretKey();

    expect($key2)
        ->not()->toBeNull()
        ->toBeString()
        ->not()->toBe($key1);
});

it('cannot return a otpauth url when user is not yet set up', function () {
    $this->provider->getQrCode($this->user);
})->throws(TwoFactorNotSetUpException::class);

it('can return a otpauth url when user is set up', function () {
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);

    expect($this->provider->getQrCode($this->user))
        ->toStartWith('otpauth://');
});

it('can return the svg markup for the qr code', function () {
    // create a two factor user
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);

    expect($this->provider->getQrCodeSvg($this->user))
        ->toStartWith('<svg');
});

it('can verify a one time code', function () {
    // create a two factor user
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);

    $code = '111111';
    while ($code === '111111') {
        // create a code that is NOT 111111
        $code = getCode($this->user);
    }

    // should verify correctly
    expect($code)
        ->not()->toBe('111111')
        ->and($this->provider->verify($this->provider->getSecretKey($this->user), $code))
        ->toBeTrue();

    // try with '111111', and should fail
    $code = '111111';
    expect($code)
        ->toBe('111111')
        ->and($this->provider->verify($this->provider->getSecretKey($this->user), $code))
        ->toBeFalse();
});
