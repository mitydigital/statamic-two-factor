<?php

namespace MityDigital\StatamicTwoFactor\Tests;

use Facades\Statamic\Version;
use Illuminate\Encryption\Encrypter;
use MityDigital\StatamicTwoFactor\ServiceProvider;
use MityDigital\StatamicTwoFactor\Tests\Models\User;
use Statamic\Console\Processes\Composer;
use Statamic\Stache\Stores\UsersStore;
use Statamic\Statamic;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    public static $migrationsGenerated = false;

    protected $shouldFakeVersion = true;

    protected string $addonServiceProvider = ServiceProvider::class;

    protected function setUp(): void
    {
        parent::setUp();

        if (env('TWO_FACTOR_USER_MODE', 'file') === 'eloquent') {
            config(['statamic.users.repository' => 'eloquent']);
        }

        if ($this->shouldFakeVersion) {
            Version::shouldReceive('get')
                ->andReturn(Composer::create(__DIR__.'/../')->installedVersion(Statamic::PACKAGE));
        }

        $this->loadMigrationsFrom(__DIR__.'/__migrations__');
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = [
            'assets',
            'cp',
            'forms',
            'static_caching',
            'stache',
            'system',
            'users',
        ];

        foreach ($configs as $config) {
            $app['config']->set(
                "statamic.$config",
                require (__DIR__."/../vendor/statamic/cms/config/{$config}.php")
            );
        }

        $app['config']->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey($app['config']['app.cipher'])
        ));

        if (env('TWO_FACTOR_USER_MODE', 'file') === 'eloquent') {
            $app['config']->set('auth.providers.users.driver', 'eloquent');
            if (true) {
                $app['config']->set('auth.providers.users.model', User::class);
            }
        } else {
            $app['config']->set('auth.providers.users.driver', 'statamic');
            $app['config']->set('statamic.users.repository', 'file');

            $app['config']->set('statamic.stache.stores.users', [
                'class' => UsersStore::class,
                'directory' => __DIR__.'/__fixtures__/users',
            ]);
        }

        $app['config']->set('statamic.editions.pro', true);

        $app['config']->set('statamic-two-factor.enabled', true);
        $app['config']->set('statamic-two-factor.attempts', 3);

    }
}
