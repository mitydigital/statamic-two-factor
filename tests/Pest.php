<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as Router;
use MityDigital\StatamicTwoFactor\Support\Google2FA;
use MityDigital\StatamicTwoFactor\Support\RecoveryCode;
use MityDigital\StatamicTwoFactor\Tests\TestCase;
use Statamic\Facades\User;

uses(TestCase::class)->in('Actions', 'Commands', 'Concerns',
    'Fieldtypes', 'Http', 'Listeners', 'Notifications',
    'Support', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createUser(): Statamic\Auth\File\User|\Statamic\Auth\Eloquent\User
{
    return User::make()
        ->makeSuper()
        ->set('name', 'Peter Parker')
        ->email('peter.parker@spiderman.com')
        ->set('password', 'secret')
        ->save();
}

function createUserWithTwoFactor(): Statamic\Auth\File\User|\Statamic\Auth\Eloquent\User
{
    $user = createUser();

    $user->set('two_factor_locked', false);
    $user->set('two_factor_confirmed_at', now());
    $user->set('two_factor_completed', now());
    $user->set('two_factor_secret',
        encrypt(app(Google2FA::class)->generateSecretKey()));
    $user->set('two_factor_recovery_codes', encrypt(json_encode(Collection::times(8, function () {
        return RecoveryCode::generate();
    })->all())));

    $user->save();

    return $user;
}

function getCode()
{
    $provider = app(Google2FA::class);

    // get a one-time code (so we can make sure we have a wrong one in the test)
    $internalProvider = app(\PragmaRX\Google2FA\Google2FA::class);

    return $internalProvider->getCurrentOtp($provider->getSecretKey());
}

function getPrivateProperty($className, $propertyName): ReflectionProperty
{
    $reflector = new ReflectionClass($className);
    $property = $reflector->getProperty($propertyName);
    $property->setAccessible(true);

    return $property;
}

// Based on:
// https://gist.github.com/juampi92/fff250719122a596c716c64e5b0afef6
function createRequestWithParameters(string $routeName, array $parameters = [], string $class = Request::class)
{
    // Find the route properties.
    $route = Router::getRoutes()->getByName($routeName);

    throw_if(is_null($route),
        new Exception("[Pest.php createRequestWithParameters] Couldn't find route by the name of {$routeName}."));

    // Recreate the full url
    $fullUrl = route($routeName, $parameters);

    $method = $route->methods()[0];
    $uri = $route->uri;

    $request = $class::create($fullUrl);
    $request->setRouteResolver(function () use ($request, $method, $uri) {
        // Associate Route to request so we can access route parameters.
        return (new Route($method, $uri, []))->bind($request);
    });

    return $request;
}

/*
 * See https://twitter.com/lukedowning19/status/1557767236693237761?s=11&t=09IS05bjk8nGHLvjYdzx1A
 */
function trackActions(array $actions): void
{
    foreach ($actions as $action => $times) {
        $action = is_string($action) ? $action : $times;
        $times = is_string($times) ? 1 : $times;

        $real = app($action);
        $mock = Mockery::mock($action);

        Container::getInstance()->instance($action, $mock);

        if ($times === 0 || $times === false) {
            $mock->shouldNotReceive('__invoke');
        } else {
            $mock
                ->makePartial()
                ->shouldReceive('__invoke')
                ->times($times)
                ->andReturnUsing(fn(...$args) => $real(...$args))
                ->globally()
                ->ordered();
        }
    }
}
