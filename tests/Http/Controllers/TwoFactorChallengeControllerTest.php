<?php

use MityDigital\StatamicTwoFactor\Actions\ChallengeTwoFactorAuthentication;
use MityDigital\StatamicTwoFactor\Http\Controllers\TwoFactorChallengeController;

beforeEach(function () {
    $this->user = createUserWithTwoFactor();
    $this->user->set('super', true); // make a super user to get CP access

    session()->put([
        'login.id' => $this->user->getKey(),
        'login.remember' => false,
    ]);
});

it('shows the two factor challenge view', function () {
    $this->get(action([TwoFactorChallengeController::class, 'index']))
        ->assertViewIs('statamic-two-factor::challenge');
});

it('increases the number of failed attempts when an attempt fails', function () {
    // by default, there should be no attempts
    expect(session()->get('statamic_two_factor_attempts', 0))->toBe(0);

    // attempt, with a failed challenge
    $this->followingRedirects()
        ->post(action([TwoFactorChallengeController::class, 'store']), [
            'code' => '', // will fail
        ])
        ->assertViewIs('statamic-two-factor::challenge');

    expect(session()->get('statamic_two_factor_attempts'))->toBe(1);
});

it('locks the user after a number of failed attempts', function () {
    // configured to have 3 failed attempts
    $this->followingRedirects()
        ->post(action([TwoFactorChallengeController::class, 'store']), [
            'code' => '', // will fail
        ])
        ->assertViewIs('statamic-two-factor::challenge');

    expect(session()->get('statamic_two_factor_attempts'))->toBe(1);

    $this->followingRedirects()
        ->post(action([TwoFactorChallengeController::class, 'store']), [
            'code' => '', // will fail
        ])
        ->assertViewIs('statamic-two-factor::challenge');

    expect(session()->get('statamic_two_factor_attempts'))->toBe(2);

    $this->followingRedirects()
        ->post(action([TwoFactorChallengeController::class, 'store']), [
            'code' => '', // will fail
        ])
        ->assertViewIs('statamic-two-factor::locked');
});

it('uses the challenge two factor authentication action', function () {
    trackActions([
        ChallengeTwoFactorAuthentication::class => 1,
    ]);

    $this->post(action([TwoFactorChallengeController::class, 'store']), [
        'code' => '',
    ]);
});

it('sets a referrer url when one is present and not a two factor route', function () {

    expect(session()->get('statamic_two_factor_referrer'))->toBeNull();

    $this->get(action([TwoFactorChallengeController::class, 'index']), [
        'referer' => cp_route('collections.index'),
    ]);

    expect(session()->get('statamic_two_factor_referrer'))
        ->not()->toBeNull()
        ->toBe(cp_route('collections.index'));
});

it('forgets the number of failed attempts when succeeded', function () {
    // count should be null
    expect(session()->get('statamic_two_factor_attempts'))->toBe(null);

    // fail attempt 1
    $this->followingRedirects()
        ->post(action([TwoFactorChallengeController::class, 'store']), [
            'code' => '',
        ]);

    // count should be 1
    expect(session()->get('statamic_two_factor_attempts'))->toBe(1);

    // succeed attempt 2
    $this->post(action([TwoFactorChallengeController::class, 'store']), [
        'code' => getCode($this->user),
    ]);

    // count should be null
    expect(session()->get('statamic_two_factor_attempts'))->toBe(null);
});

it('redirects to the cp after a successful challenge', function () {
    // post, and redirect
    $this->post(action([TwoFactorChallengeController::class, 'store']), [
        'code' => getCode($this->user),
    ])
        ->assertRedirect(cp_route('index'));
});

it('redirects to the referrer after a successful challenge', function () {
    // get, and set referrer
    $this->get(action([TwoFactorChallengeController::class, 'index']), [
        'referer' => cp_route('collections.index'),
    ]);

    // post, and redirect
    $this->post(action([TwoFactorChallengeController::class, 'store']), [
        'code' => getCode($this->user),
    ])
        ->assertRedirect(cp_route('collections.index'));
});
