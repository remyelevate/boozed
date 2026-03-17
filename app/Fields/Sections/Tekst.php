<?php

namespace App\Fields\Sections;

class Tekst
{
    public static function get()
    {
        return [
            'key'      => 'layout_boozed_tekst',
            'name'     => 'tekst',
            'label'    => 'Text',
            'display'  => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_tekst_content',
                    'label'   => 'Content',
                    'name'    => 'tekst_content',
                    'type'    => 'wysiwyg',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_tekst_button_text',
                    'label'   => 'Button text',
                    'name'    => 'tekst_button_text',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'         => 'field_boozed_tekst_button_url',
                    'label'       => 'Button URL',
                    'name'        => 'tekst_button_url',
                    'type'        => 'link',
                    'return_format' => 'url',
                    'placeholder' => 'https://example.com',
                    'wrapper'     => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_tekst_button_alignment',
                    'label'         => 'Button alignment',
                    'name'          => 'tekst_button_alignment',
                    'type'          => 'button_group',
                    'choices'       => [
                        'left'   => 'Left',
                        'center' => 'Center',
                        'right'  => 'Right',
                    ],
                    'default_value' => 'left',
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
