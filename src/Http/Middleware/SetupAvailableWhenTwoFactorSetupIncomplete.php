<?php

namespace MityDigital\StatamicTwoFactor\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SetupAvailableWhenTwoFactorSetupIncomplete
{
    /**
     * When two factor setup is completed, redirect to the dashboard (used by setup routes)
     *
     * @return \Illuminate\Contracts\Foundation\Application|Application|RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // IS TWO FACTOR ENABLED?
        // we only need to do any further checks if two factor is enabled
        if (config('statamic-two-factor.enabled')) {
            // get the user
            $user = $request->user();

            // is two factor set up?
            if ($user->two_factor_completed) {
                // redirect to the home page
                return redirect(cp_route('index'));
            }
        }

        // all good, continue
        return $next($request);
    }
}
