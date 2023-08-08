<?php

namespace MityDigital\StatamicTwoFactor\Tests;

use Facades\Statamic\Version;
use Illuminate\Encryption\Encrypter;
use MityDigital\StatamicTwoFactor\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Statamic\Console\Processes\Composer;
use Statamic\Extend\Manifest;
use Statamic\Providers\StatamicServiceProvider;
use Statamic\Stache\Stores\UsersStore;
use Statamic\Statamic;

abstract class TestCase extends OrchestraTestCase
{
    protected $shouldFakeVersion = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        if ($this->shouldFakeVersion) {
            Version::shouldReceive('get')
                ->andReturn(Composer::create(__DIR__.'/../')->installedVersion(Statamic::PACKAGE));
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            StatamicServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'mitydigital/statamic-two-factor' => [
                'id' => 'mitydigital/statamic-two-factor',
                'namespace' => 'MityDigital\\StatamicTwoFactor',
            ],
        ];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = [
            'assets',
            'cp',
            'forms',
            'static_caching',
            'sites',
            'stache',
            'system',
            'users',
        ];

        foreach ($configs as $config) {
            $app['config']->set(
                "statamic.$config",
                require(__DIR__."/../vendor/statamic/cms/config/{$config}.php")
            );
        }

        $app['config']->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey($app['config']['app.cipher'])
        ));

        $app['config']->set('auth.providers.users.driver', 'statamic');
        $app['config']->set('statamic.users.repository', 'file');

        $app['config']->set('statamic.stache.stores.users', [
            'class' => UsersStore::class,
            'directory' => __DIR__.'/__fixtures__/users',
        ]);

        $app['config']->set('statamic.editions.pro', true);

        $app['config']->set('statamic-two-factor.enabled', true);
        $app['config']->set('statamic-two-factor.attempts', 3);

    }
}
