<?php

namespace MityDigital\StatamicTwoFactor\UpdateScripts;

use Statamic\Facades\User;
use Statamic\Fields\Field;
use Statamic\UpdateScripts\UpdateScript;

class UpdateUserBlueprint extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return true;
    }

    public function update()
    {
        // do we have the "two_factor" fieldtype?
        $blueprint = User::blueprint();
        if (!$blueprint
                ->fields()
                ->all()
                ->filter(fn(Field $field) => $field->type() === 'two_factor')
                ->count()) {
            // we do not, so let's add it
            $contents = $blueprint->contents();

            $contents['tabs'][array_key_first($contents['tabs'])]['sections'][] = [
                'display' => 'Two Factor',
                'fields' => [
                    [
                        'handle' => 'two_factor',
                        'field' => [
                            'display' => __('statamic-two-factor::messages.blueprint_field'),
                            'hide_display' => true,
                            'type' => 'two_factor',
                            'listable' => false,
                        ]
                    ]
                ]
            ];

            $blueprint->setContents($contents);

            $blueprint->save();

            $this->console()->info(__('statamic-two-factor::messages.blueprint_field_success'));
        }
    }
}
