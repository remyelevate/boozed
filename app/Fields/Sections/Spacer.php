<?php

namespace App\Fields\Sections;

class Spacer
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_spacer',
            'name'       => 'spacer',
            'label'      => 'Spacer',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_spacer_height',
                    'label'         => 'Height (px)',
                    'name'          => 'spacer_height',
                    'type'          => 'number',
                    'default_value' => 68,
                    'min'           => 0,
                    'step'          => 1,
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
