<?php

use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use MityDigital\StatamicTwoFactor\ServiceProvider;

it('has the correct middleware defined', function () {
    $provider = app(ServiceProvider::class, ['app' => app()]);
    $middleware = getPrivateProperty(ServiceProvider::class, 'middlewareGroups');

    expect($middleware->getValue($provider))
        ->toMatchArray([
            'statamic.cp.authenticated' => [
                EnforceTwoFactor::class,
            ],
        ]);
});
