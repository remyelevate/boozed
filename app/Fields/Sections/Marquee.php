<?php

namespace App\Fields\Sections;

class Marquee
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_marquee',
            'name'       => 'marquee',
            'label'      => 'Marquee',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_marquee_caption',
                    'label'         => 'Caption',
                    'name'          => 'marquee_caption',
                    'type'          => 'text',
                    'default_value' => '',
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
