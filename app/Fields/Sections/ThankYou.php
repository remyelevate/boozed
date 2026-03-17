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
                    'key'     => 'field_boozed_thank_you_description',
                    'label'   => 'Description',
                    'name'    => 'thank_you_description',
                    'type'    => 'wysiwyg',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_thank_you_button_text',
                    'label'   => 'Button text',
                    'name'    => 'thank_you_button_text',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_thank_you_button_url',
                    'label'   => 'Button URL',
                    'name'    => 'thank_you_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
            ],
        ];
    }
}
