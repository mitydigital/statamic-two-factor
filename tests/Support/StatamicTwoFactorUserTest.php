<?php

use MityDigital\StatamicTwoFactor\Facades\StatamicTwoFactorUser;
use Statamic\Facades\Role;

it('gets the current user', function () {
    // null when not logged in
    expect(StatamicTwoFactorUser::get())->toBeNull();

    // create a user
    $user = createUser();

    // log in
    $this->actingAs($user);

    // get a user
    expect(StatamicTwoFactorUser::get())
        ->id->toBe($user->id);
});

it('sets, gets and clears the last challenged for the user', function () {
    // create user
    $user = createUserWithTwoFactor();
    $this->actingAs($user);
    $otherUser = createUserWithTwoFactor();

    // should be null
    expect(StatamicTwoFactorUser::getLastChallenged())->toBeNull();

    // set the challenge
    StatamicTwoFactorUser::setLastChallenged();

    // should be set
    expect(StatamicTwoFactorUser::getLastChallenged())->not()->toBeNull();

    // clear the challenge
    StatamicTwoFactorUser::clearLastChallenged();

    // should be null
    expect(StatamicTwoFactorUser::getLastChallenged())->toBeNull();
});

it('correctly determines if two factor is enforceable', function () {
    // create a user
    $user = createUserWithTwoFactor();
    $this->actingAs($user);

    // create a role
    $enforceableRole = Role::make('enforceable_role')->save();
    $standardRole = Role::make('standard_role')->save();

    //
    // configure to be null (the initial default - all users enforced)
    //
    config()->set('statamic-two-factor.enforced_roles', null);

    // super user is always enforceable
    $user->makeSuper();
    expect($user->isSuper())->toBeTrue()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    // disable super
    $user->set('super', false)->save();
    expect($user->isSuper())->toBeFalse();

    // enforced roles is null - so any user should be enforced
    expect(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    // assign a role
    $user->assignRole($enforceableRole)->save();
    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeFalse()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    $user->removeRole($enforceableRole->handle());
    $user->assignRole($standardRole->handle());
    expect($user->hasRole($enforceableRole))->toBeFalse()
        ->and($user->hasRole($standardRole))->toBeTrue()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    $user->assignRole($enforceableRole->handle());
    $user->assignRole($standardRole->handle());
    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeTrue()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    //
    // configure to be an array, but empty
    //
    config()->set('statamic-two-factor.enforced_roles', []);

    // assigned roles, but none are enforced
    expect(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeFalse();

    //
    // configure to be an array, with actual roles
    //
    config()->set('statamic-two-factor.enforced_roles', [
        $enforceableRole->handle(),
    ]);

    $user->removeRole($enforceableRole->handle());
    $user->removeRole($standardRole->handle());

    expect($user->hasRole($enforceableRole))->toBeFalse()
        ->and($user->hasRole($standardRole))->toBeFalse();

    // enforceable role
    $user->assignRole($enforceableRole->handle());
    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeFalse()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    // standard role
    $user->removeRole($enforceableRole->handle());
    $user->assignRole($standardRole->handle());
    expect($user->hasRole($enforceableRole))->toBeFalse()
        ->and($user->hasRole($standardRole))->toBeTrue()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeFalse();

    // both roles
    $user->assignRole($enforceableRole->handle());
    $user->assignRole($standardRole->handle());
    expect($user->hasRole($enforceableRole))->toBeTrue()
        ->and($user->hasRole($standardRole))->toBeTrue()
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable())->toBeTrue();

    //
    // it works for a specified user
    //
    $otherUser = createUser(false);
    expect(StatamicTwoFactorUser::isTwoFactorEnforceable($user))->toBeTrue() // the last test
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable($otherUser))->toBeFalse(); // not enforced

    $otherUser->makeSuper();
    expect(StatamicTwoFactorUser::isTwoFactorEnforceable($user))->toBeTrue() // the last test
        ->and(StatamicTwoFactorUser::isTwoFactorEnforceable($otherUser))->toBeTrue(); // super is enforced
});
