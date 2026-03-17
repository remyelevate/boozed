<?php

namespace App\Fields\Sections;

class Cta
{
    public static function get()
    {
        return [
            'key'      => 'layout_boozed_cta',
            'name'     => 'cta',
            'label'    => 'CTA',
            'display'  => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_cta_title',
                    'label'         => 'Title',
                    'name'          => 'cta_title',
                    'type'          => 'text',
                    'default_value' => 'Get in touch',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_cta_button_text',
                    'label'         => 'Button text',
                    'name'          => 'cta_button_text',
                    'type'          => 'text',
                    'default_value' => 'Contact us',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_cta_button_url',
                    'label'         => 'Button URL',
                    'name'          => 'cta_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
