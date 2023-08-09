<?php

namespace MityDigital\StatamicTwoFactor\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\User;
use Statamic\Fields\Field;

class UpdateUserBlueprintCommand extends Command
{
    protected $signature = 'two-factor:update-user-blueprint';

    protected $description = 'Adds the "two_factor" fieldtype to the User Blueprint.';

    public function handle(): void
    {
        $blueprint = User::blueprint();
        if (! $blueprint
            ->fields()
            ->all()
            ->filter(fn (Field $field) => $field->type() === 'two_factor')
            ->count()) {
            // we do not, so let's add it
            $contents = $blueprint->contents();

            $contents['tabs'][array_key_first($contents['tabs'])]['sections'][] = [
                'display' => __('statamic-two-factor::messages.blueprint_tab'),
                'fields' => [
                    [
                        'handle' => 'two_factor',
                        'field' => [
                            'display' => __('statamic-two-factor::messages.blueprint_field'),
                            'hide_display' => true,
                            'type' => 'two_factor',
                            'listable' => true,
                        ],
                    ],
                ],
            ];

            $blueprint->setContents($contents);

            $blueprint->save();

            $this->info(__('statamic-two-factor::messages.blueprint_field_success'));
        }
    }
}
