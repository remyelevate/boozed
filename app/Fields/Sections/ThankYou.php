<?php

namespace App\Fields\Sections;

class ThankYou
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_thank_you',
            'name'       => 'thank_you',
            'label'      => 'Thank You',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_thank_you_title',
                    'label'   => 'Title',
                    'name'    => 'thank_you_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_thank_you_subtitle',
                    'label'   => 'Subtitle',
                    'name'    => 'thank_you_subtitle',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_thank_you_button_label',
                    'label'   => 'Button label',
                    'name'    => 'thank_you_button_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_thank_you_button_link',
                    'label'   => 'Button link',
                    'name'    => 'thank_you_button_link',
                    'type'    => 'url',
                    'wrapper' => ['width' => '50'],
                ],
            ],
        ];
    }
}
