<?php

namespace MityDigital\StatamicTwoFactor;

use Illuminate\Contracts\Foundation\Application;
use MityDigital\StatamicTwoFactor\Console\Commands\UpdateUserBlueprintCommand;
use MityDigital\StatamicTwoFactor\Fieldtypes\TwoFactor as TwoFactorFieldtype;
use MityDigital\StatamicTwoFactor\Http\Middleware\EnforceTwoFactor;
use MityDigital\StatamicTwoFactor\Listeners\UserSavedListener;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use MityDigital\StatamicTwoFactor\Support\StatamicTwoFactorUser;
use Statamic\Events\UserSaved;
use Statamic\Http\Controllers\CP\Auth\LoginController;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        UpdateUserBlueprintCommand::class,
    ];

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
        $this->app->bind('statamicTwoFactorUser', function ($app) {
            return new StatamicTwoFactorUser();
        });
        $this->app->alias(StatamicTwoFactorUser::class, 'statamicTwoFactorUser');

        $this->app->singleton(Google2FA::class, function (Application $app) {
            return new Google2FA();
        });

        $this->app->extend(LoginController::class, function () {
            return new Http\Controllers\LoginController();
        });

        // publish migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ],
                'statamic-two-factor-migrations'
            );
        }

        // after install
        Statamic::afterInstalled(function ($command) {
            $command->call('two-factor:update-user-blueprint');
        });
    }
}
