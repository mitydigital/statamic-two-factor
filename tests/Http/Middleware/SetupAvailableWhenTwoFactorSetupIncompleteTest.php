<?php

use Illuminate\Http\Request;
use MityDigital\StatamicTwoFactor\Http\Middleware\SetupAvailableWhenTwoFactorSetupIncomplete;

it('allows access to a setup route when two factor is not set up', function () {
    // create a non-two factor user
    $request = Request::create(cp_route('statamic-two-factor.setup'));
    $request->setUserResolver(fn () => createUser());

    $middleware = new SetupAvailableWhenTwoFactorSetupIncomplete();

    $response = $middleware->handle($request, function () {
    });

    expect($response)->toBeNull();
});

it('does not allow access when two factor is set up', function () {
    // create a two factor user
    $request = Request::create(cp_route('statamic-two-factor.setup'));
    $request->setUserResolver(fn () => createUserWithTwoFactor());

    $middleware = new SetupAvailableWhenTwoFactorSetupIncomplete();

    $response = $middleware->handle($request, function () {
    });

    expect($response)
        ->isRedirect(cp_route('index'))->toBeTrue()
        ->status()->toBe(302);
});
