<?php

namespace MityDigital\StatamicTwoFactor;

use Illuminate\Contracts\Foundation\Application;
use MityDigital\StatamicTwoFactor\Fieldtypes\TwoFactor as TwoFactorFieldtype;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use MityDigital\StatamicTwoFactor\Listeners\UserSavedListener;
use Statamic\Events\UserSaved;
use Statamic\Http\Controllers\CP\Auth\LoginController;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        TwoFactorFieldtype::class,
    ];

    protected $listen = [
        UserSaved::class => [
            UserSavedListener::class,
        ],
    ];

    protected $middlewareGroups = [
        'statamic.cp.authenticated' => [
            EnforceTwoFactor::class,
        ],
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $vite = [
        'input' => [
            'resources/css/cp.css',
            'resources/js/cp.js',
        ],
        'publicDirectory' => 'resources/dist',
    ];

    public function bootAddon()
    {
        $this->app->singleton(\MityDigital\StatamicTwoFactor\Support\Google2FA::class, function (Application $app) {
            return new \MityDigital\StatamicTwoFactor\Support\Google2FA();
        });

        $this->app->extend(LoginController::class, function () {
            return new \MityDigital\StatamicTwoFactor\Http\Controllers\LoginController();
        });

        // publish migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ],
                'statamic-two-factor-migrations'
            );
        }
    }
}
