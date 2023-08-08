<?php

use MityDigital\StatamicTwoFactor\Fieldtypes\TwoFactor;

beforeEach(function () {
    $this->fieldtype = new TwoFactor();

    // enable
    config()->set('statamic-two-factor.enabled', true);

    // create a user
    $this->user = createUserWithTwoFactor();
    $this->actingAs($this->user);

    // create the edit request
    $request = createRequestWithParameters('statamic.cp.users.edit', [
        'user' => $this->user->id,
    ]);

    app()->instance('request', $request);
});

it('it returns the minimal configuration when two factor is disabled', function () {
    // disable
    config()->set('statamic-two-factor.enabled', false);

    expect(config('statamic-two-factor.enabled'))
        ->toBeFalse()
        ->and($this->fieldtype->preload())
        ->toMatchArray([
            'enabled' => false,

            'is_locked' => false,
            'is_me' => false,
            'is_setup' => false,
            'is_user_edit' => false,

            'routes' => [],
        ]);
});

it('is marked as enabled when two factor is enabled', function () {
    expect($this->fieldtype->preload()['enabled'])
        ->toBeTrue();
});

it('correctly returns "is locked"', function () {
    // user is not locked
    expect($this->user->two_factor_locked)
        ->not()->toBeTrue() // null or false
        ->and($this->fieldtype->preload()['is_locked'])
        ->toBeFalse();

    // lock the user
    $this->user->set('two_factor_locked', true);

    // should return true
    expect($this->user->two_factor_locked)
        ->toBeTrue()
        ->and($this->fieldtype->preload()['is_locked'])
        ->toBeTrue();
});

it('correctly returns "is me"', function () {
    // the initial test will act as me
    expect($this->fieldtype->preload()['is_me'])
        ->toBeTrue();

    // create a new user
    $this->actingAs(createUserWithTwoFactor());

    // should return true
    expect($this->fieldtype->preload()['is_me'])
        ->toBeFalse();
});

it('correctly returns "is setup"', function () {
    // the initial test will act as a user who is setup
    expect($this->fieldtype->preload()['is_setup'])
        ->toBeTrue();

    // create a new user WITHOUT TWO FACTOR
    $user = createUser();
    $request = createRequestWithParameters('statamic.cp.users.edit', [
        'user' => $user->id,
    ]);

    app()->instance('request', $request);

    // should return true
    expect($this->fieldtype->preload()['is_setup'])
        ->toBeFalse();
});

it('correctly returns "is_user_edit"', function () {
    // initial setup is for true
    expect($this->fieldtype->preload()['is_user_edit'])
        ->toBeTrue();

    // create a new request
    $request = createRequestWithParameters('statamic.cp.dashboard');

    app()->instance('request', $request);

    expect($this->fieldtype->preload()['is_user_edit'])
        ->toBeFalse();
});

it('correctly returns "routes" for me', function () {
    //
    // running the test as ME
    //   locked variable
    //   recovery codes exists
    //   reset is present
    //

    //
    // when unlocked
    //
    // expect the correct routes and keys
    $config = $this->fieldtype->preload();
    expect($config['routes'])
        ->toHaveKeys([
            'locked',
            'recovery_codes',
            'reset',
        ])
        ->and($config['routes']['recovery_codes'])
        ->toHaveKeys([
            'show',
            'generate',
        ])
        ->and($config['routes']['locked'])
        ->toBeNull()
        ->and($config['routes']['recovery_codes']['show'])->toBe(cp_route('statamic-two-factor.user.recovery-codes.show',
            ['user' => $this->user->id]))
        ->and($config['routes']['recovery_codes']['generate'])->toBe(cp_route('statamic-two-factor.user.recovery-codes.generate',
            ['user' => $this->user->id]))
        ->and($config['routes']['reset'])->toBe(cp_route('statamic-two-factor.user.reset',
            ['user' => $this->user->id]));

    // when the user is locked
    $this->user->set('two_factor_locked', true)->save();
    $config = $this->fieldtype->preload();
    expect($config['routes'])
        ->toHaveKeys([
            'locked',
            'recovery_codes',
            'reset',
        ])
        ->and($config['routes']['recovery_codes'])
        ->toHaveKeys([
            'show',
            'generate',
        ])
        ->and($config['routes']['locked'])->toBe(cp_route('statamic-two-factor.user.unlock',
            ['user' => $this->user->id]))
        ->and($config['routes']['recovery_codes']['show'])->toBe(cp_route('statamic-two-factor.user.recovery-codes.show',
            ['user' => $this->user->id]))
        ->and($config['routes']['recovery_codes']['generate'])->toBe(cp_route('statamic-two-factor.user.recovery-codes.generate',
            ['user' => $this->user->id]))
        ->and($config['routes']['reset'])->toBe(cp_route('statamic-two-factor.user.reset',
            ['user' => $this->user->id]));
});

it('correctly returns "routes" for another user', function () {
    //
    // running the test as ANOTHER USER
    //   locked variable
    //   recovery codes not present
    //   reset is present
    //

    // create a new request
    $user = createUserWithTwoFactor();
    $request = createRequestWithParameters('statamic.cp.users.edit', [
        'user' => $user->id,
    ]);
    app()->instance('request', $request);

    //
    // when unlocked
    //
    // expect the correct routes and keys
    $config = $this->fieldtype->preload();
    expect($config['routes'])
        ->toHaveKeys([
            'locked',
            'recovery_codes',
            'reset',
        ])
        ->and($config['routes']['recovery_codes'])
        ->toHaveKeys([
            'show',
            'generate',
        ])
        ->and($config['routes']['locked'])
        ->toBeNull()
        ->and($config['routes']['recovery_codes']['show'])->toBeNull()
        ->and($config['routes']['recovery_codes']['generate'])->toBeNull()
        ->and($config['routes']['reset'])
        ->toBe(cp_route('statamic-two-factor.user.reset',
            ['user' => $user->id]));

    // when the user is locked
    $user->set('two_factor_locked', true)->save();
    $config = $this->fieldtype->preload();
    expect($config['routes'])
        ->toHaveKeys([
            'locked',
            'recovery_codes',
            'reset',
        ])
        ->and($config['routes']['recovery_codes'])
        ->toHaveKeys([
            'show',
            'generate',
        ])
        ->and($config['routes']['locked'])->toBe(cp_route('statamic-two-factor.user.unlock',
            ['user' => $user->id]))
        ->and($config['routes']['recovery_codes']['show'])->toBeNull()
        ->and($config['routes']['recovery_codes']['generate'])->toBeNull()
        ->and($config['routes']['reset'])->toBe(cp_route('statamic-two-factor.user.reset',
            ['user' => $user->id]));
});
