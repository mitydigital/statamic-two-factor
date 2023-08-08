<?php

use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use MityDigital\StatamicTwoFactor\ServiceProvider;

function getPrivateProperty($className, $propertyName): ReflectionProperty
{
    $reflector = new ReflectionClass($className);
    $property = $reflector->getProperty($propertyName);
    $property->setAccessible(true);

    return $property;
}

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
