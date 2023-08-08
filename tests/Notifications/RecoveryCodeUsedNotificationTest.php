<?php

use Illuminate\Support\Facades\Notification;
use MityDigital\StatamicTwoFactor\Notifications\RecoveryCodeUsedNotification;

beforeEach(function () {
    $this->user = createUser();
});

it('contains the expected content', function () {
    Notification::fake();

    $user = $this->user;

    $user->notify(new RecoveryCodeUsedNotification());

    Notification::assertSentTo($user, RecoveryCodeUsedNotification::class,
        function ($notification, $channels) use ($user) {
            $mailData = $notification->toMail($user);

            // subject, intro, outro and action
            expect($mailData->subject)
                ->toBe(__('statamic-two-factor::messages.recovery_code_used_subject'))
                ->and($mailData->introLines)
                ->toBeArray()
                ->toHaveCount(2)
                ->toMatchArray([
                    __('statamic-two-factor::messages.recovery_code_used_body'),
                    __('statamic-two-factor::messages.recovery_code_used_body_2'),
                ])
                ->and($mailData->outroLines)
                ->toBeArray()
                ->toHaveCount(1)
                ->toMatchArray([
                    __('statamic-two-factor::messages.recovery_code_used_body_3'),
                ])
                ->and($mailData->actionText)
                ->toBe(__('statamic-two-factor::messages.recovery_code_used_action'))
                ->and($mailData->actionUrl)
                ->toBe(cp_route('account'));

            return true;
        });
});
