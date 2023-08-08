<?php

use Illuminate\Support\Facades\File;
use MityDigital\StatamicTwoFactor\Console\Commands\UpdateUserBlueprintCommand;
use Statamic\Facades\Blueprint;
use Statamic\Facades\User;
use Statamic\Fields\Field;

beforeEach(function () {
    File::deleteDirectory(__DIR__.'/../__fixtures__/blueprints');
    Blueprint::setDirectory(__DIR__.'/../__fixtures__/blueprints');

    $this->blueprint = User::blueprint();
    $this->command = app(UpdateUserBlueprintCommand::class);
});

it('has the correct signature', function () {
    $signature = getPrivateProperty(UpdateUserBlueprintCommand::class,
        'signature');

    expect($signature->getValue($this->command))->toBe('two-factor:update-user-blueprint');
});

it('adds the "two_factor" fieldtype to the user blueprint', function () {
    // ensure the field doesn't exist already
    expect($this->blueprint
        ->fields()
        ->all()
        ->filter(fn (Field $field) => $field->type() === 'two_factor')
        ->count())
        ->toBe(0);

    // run the command
    $this->artisan('two-factor:update-user-blueprint');

    expect($this->blueprint
        ->fields()
        ->all()
        ->filter(fn (Field $field) => $field->type() === 'two_factor')
        ->count())
        ->toBe(1);
});

it('does not add a "two_factor" field if it already exists', function () {
    // ensure the field doesn't exist already
    expect($this->blueprint
        ->fields()
        ->all()
        ->filter(fn (Field $field) => $field->type() === 'two_factor')
        ->count())
        ->toBe(0);

    // run the command
    $this->artisan('two-factor:update-user-blueprint');

    expect($this->blueprint
        ->fields()
        ->all()
        ->filter(fn (Field $field) => $field->type() === 'two_factor')
        ->count())
        ->toBe(1);

    // run the command
    $this->artisan('two-factor:update-user-blueprint');

    expect($this->blueprint
        ->fields()
        ->all()
        ->filter(fn (Field $field) => $field->type() === 'two_factor')
        ->count())
        ->toBe(1);
});
