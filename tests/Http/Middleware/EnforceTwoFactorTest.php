<?php

use Illuminate\Http\Request;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Spatie\PestPluginTestTime\testTime;

it('moves to the next middleware when two factor is disabled', function () {
    // disable
    config()->set('statamic-two-factor.enabled', false);

    // create the request
    $request = Request::create(cp_route('index'));
    $request->setUserResolver(fn () => createUser());

    $middleware = new EnforceTwoFactor();

    $response = $middleware->handle($request, function () {
    });

    expect($response)
        ->toBeNull();

    // enable
    config()->set('statamic-two-factor.enabled', true);

    // create the request
    $request = Request::create(cp_route('index'));
    $request->setUserResolver(fn () => createUser());

    $middleware = new EnforceTwoFactor();

    $response = $middleware->handle($request, function () {
    });

    expect($response->isRedirection())
        ->toBeTrue();
});

it('redirects to the setup route when two factor setup is not completed', function () {
    $request = Request::create(cp_route('index'));
    $request->setUserResolver(fn () => createUser());

    $middleware = new EnforceTwoFactor();

    $response = $middleware->handle($request, function () {
    });

    expect($response->isRedirect(cp_route('statamic-two-factor.setup')))
        ->toBeTrue();
});

//
// VALIDITY enabled
//
it('redirects to the challenge when validity is enabled and there is no recent challenge or it has expired',
    function () {
        // enable validity
        config()->set('statamic-two-factor.validity', 1); // 1 minute

        // act as a user with two factor set up
        $user = createUserWithTwoFactor();
        $this->actingAs($user);

        $response = get(cp_route('collections.index'));

        // expect a redirect to the challenge view
        expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
            ->toBeTrue();

        // so far so good

        // set the time
        testTime()->freeze();

        // force a "challenge" variable (i.e. fake it)
        session()->put('statamic_two_factor', encrypt(now()));

        // try to go to the route
        $response = get(cp_route('collections.index'));

        // success!
        expect($response)
            ->status()->toBe(200);

        // jump forward 2 minutes
        testTime()->addMinutes(2);

        // try to go to the route
        $response = get(cp_route('collections.index'));

        // redirect to challenge!
        expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
            ->toBeTrue();

        // however, a POST or PATCH will not redirect - just to prevent losing things
        $response = post(cp_route('statamic-two-factor.user.recovery-codes.generate', [
            'user' => $user->id,
        ]));

        expect($response)
            ->status()->toBe(200);
    });

//
// VALIDITY disabled
//
it('redirects to the challenge when validity is disabled and there is no recent challenge', function () {
    // disable validity
    config()->set('statamic-two-factor.validity', null);

    // act as a user with two factor set up
    $this->actingAs(createUserWithTwoFactor());

    $response = get(cp_route('collections.index'));

    // expect a redirect to the challenge view
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // so far so good

    // set the time
    testTime()->freeze();

    // force a "challenge" variable (i.e. fake it)
    session()->put('statamic_two_factor', encrypt(now()));

    // skip forward 5 minutes
    testTime()->addMinutes(5);

    // try to go to the route
    $response = get(cp_route('collections.index'));

    // success!
    expect($response)
        ->status()->toBe(200);

    // add a bit more time
    testTime()->addHours(5);

    // try to go to the route
    $response = get(cp_route('collections.index'));

    // success!
    expect($response)
        ->status()->toBe(200);
});
