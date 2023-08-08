<?php

namespace MityDigital\StatamicTwoFactor\Concerns;

use Route;

trait GetsReferrerUrl
{
    protected function getReferrerUrl()
    {
        $url = url()->previous();

        $route = collect(Route::getRoutes())->first(function (\Illuminate\Routing\Route $route) use ($url) {
            return $route->matches(request()->create($url), false);
        });

        $internalRoutes = [
            'statamic.cp.statamic-two-factor.setup',
            'statamic.cp.statamic-two-factor.confirm',
            'statamic.cp.statamic-two-factor.complete',
            'statamic.cp.statamic-two-factor.challenge',
            'statamic.cp.statamic-two-factor.challenge.attempt',
            'statamic.cp.statamic-two-factor.user.recovery-codes.show',
            'statamic.cp.statamic-two-factor.user.recovery-codes.generate',
            'statamic.cp.statamic-two-factor.user.unlock',
            'statamic.cp.statamic-two-factor.user.reset',
        ];

        if (! $route || in_array($route->getName(), $internalRoutes)) {
            // there is no route, or it is a Statamic Two Factor-defined route (and we don't want to redirect there)
            return null;
        }

        return $url;
    }
}
