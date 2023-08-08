<?php

use MityDigital\StatamicTwoFactor\Concerns\GetsReferrerUrl;

uses(GetsReferrerUrl::class);

beforeEach(function () {
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);
});

function createRequestWithReferrer($referrer)
{
    $request = createRequestWithParameters('statamic.cp.dashboard');
    $request->headers->set('referer', $referrer);

    app()->instance('request', $request);

    return $request;
}

it('returns null when a statamic two factor route is the referrer', function () {

    $packageRoutes = [
        'statamic-two-factor.complete' => [],
        'statamic-two-factor.setup' => [],
        'statamic-two-factor.confirm' => [],
        'statamic-two-factor.challenge' => [],
        'statamic-two-factor.challenge.attempt' => [],
        'statamic-two-factor.user.recovery-codes.show' => ['user' => $this->user->id],
        'statamic-two-factor.user.recovery-codes.generate' => ['user' => $this->user->id],
        'statamic-two-factor.user.unlock' => ['user' => $this->user->id],
        'statamic-two-factor.user.reset' => ['user' => $this->user->id],
    ];

    foreach ($packageRoutes as $referrer => $params) {
        // create a request with the referrer set
        createRequestWithReferrer(cp_route($referrer, $params));

        // expect null returned
        expect($this->getReferrerUrl())
            ->toBeNull();
    }
});

it('returns the referrer when a non-two factor route is present', function () {
    // create the route
    $route = cp_route('dashboard');

    // create a request with the referrer set
    createRequestWithReferrer($route);

    // expect null returned
    expect($this->getReferrerUrl())
        ->toBe($route);
});
