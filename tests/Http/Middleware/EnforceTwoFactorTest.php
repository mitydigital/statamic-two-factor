<?php

use Illuminate\Http\Request;
use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use Statamic\Facades\Role;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Spatie\PestPluginTestTime\testTime;

it('moves to the next middleware when two factor is disabled', function () {
    // disable
    config()->set('statamic-two-factor.enabled', false);

    $this->actingAs(createUserWithTwoFactor());

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
    $user = createUser();
    $this->actingAs($user);

    $request = Request::create(cp_route('index'));
    $request->setUserResolver(fn () => $user);

    $middleware = new EnforceTwoFactor();

    $response = $middleware->handle($request, function () {
    });

    expect($response->isRedirect(cp_route('statamic-two-factor.setup')))
        ->toBeTrue();
});

it('redirects to the setup route when two factor setup is not completed when the user is super', function () {
    $user = createUser(true);
    $this->actingAs($user);

    config()->set('statamic-two-factor.enforced_roles', []);

    $request = Request::create(cp_route('index'));
    $request->setUserResolver(fn () => $user);

    $middleware = new EnforceTwoFactor();

    $response = $middleware->handle($request, function () {
    });

    expect($response->isRedirect(cp_route('statamic-two-factor.setup')))
        ->toBeTrue();
});

it('redirects to the setup route when two factor setup is not completed when the user has an enforced role', function () {
    $user = createUser(false);
    $this->actingAs($user);

    config()->set('statamic-two-factor.enforced_roles', []);

    $request = Request::create(cp_route('index'));
    $request->setUserResolver(fn () => $user);

    $middleware = new EnforceTwoFactor();

    $response = $middleware->handle($request, function () {
    });

    expect($response)->toBeNull(); // no response

    // create some roles
    $enforceableRole = Role::make('enforceable_role')->save();
    $standardRole = Role::make('standard_role')->save();

    config()->set('statamic-two-factor.enforced_roles', ['enforceable_role']);

    // assign role to user
    $user->assignRole($enforceableRole);
    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeFalse();

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
        StatamicTwoFactorUser::setLastChallenged($user);

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
    StatamicTwoFactorUser::setLastChallenged($user);

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

it('redirects to the challenge when super admin', function () {
    $user = createUserWithTwoFactor(true);
    $this->actingAs($user);

    // ALL roles
    config()->set('statamic-two-factor.enforced_roles', null);

    //
    // Middleware setup
    //
    $request = Request::create(cp_route('dashboard'));
    $next = function () {
        return response('No enforcement');
    };

    $middleware = app(EnforceTwoFactor::class);

    // Standard - should redirect
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // SPECIFIC roles
    config()->set('statamic-two-factor.enforced_roles', []);

    // Expect roles, should redirect
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();
});

it('redirects to the challenge two factor enabled and when no enforced roles provided', function () {
    $user = createUserWithTwoFactor(false);
    $this->actingAs($user);

    //
    // Middleware setup
    //
    $request = Request::create(cp_route('dashboard'));
    $next = function () {
        return response('No enforcement');
    };

    $middleware = app(EnforceTwoFactor::class);

    // ALL roles
    config()->set('statamic-two-factor.enforced_roles', null);

    // Standard - should redirect
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // EXPLICIT roles - none provided meaning not enforced
    config()->set('statamic-two-factor.enforced_roles', []);

    // Standard - should redirect because two factor is set up
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // create some roles
    $enforceableRole = Role::make('enforceable_role')->save();
    $standardRole = Role::make('standard_role')->save();

    // assign role to user
    $user->assignRole($enforceableRole);
    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeFalse();

    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    //
    // EXPLICIT roles - one provided meaning enforced
    //
    config()->set('statamic-two-factor.enforced_roles', [
        $enforceableRole->handle(),
    ]);

    $user->removeRole($enforceableRole);
    expect($user->roles())->toHaveCount(0);

    // no roles
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // assign role to user
    $user->assignRole($enforceableRole);

    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeFalse();

    // Standard - should redirect
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // assign both roles
    $user->assignRole($enforceableRole);
    $user->assignRole($standardRole);

    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeTrue();

    // Standard - should redirect
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();

    // remove enforceable role
    $user->removeRole($enforceableRole);
    $user->assignRole($standardRole);

    expect($user->hasRole($enforceableRole))->toBeFalse()
        ->and($user->hasRole($standardRole))->toBeTrue();

    // Standard - should redirect
    $response = $middleware->handle($request, $next);
    expect($response->isRedirect(cp_route('statamic-two-factor.challenge')))
        ->toBeTrue();
});
