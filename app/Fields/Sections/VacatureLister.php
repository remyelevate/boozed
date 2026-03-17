<?php

namespace App\Fields\Sections;

class VacatureLister
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_vacature_lister',
            'name'       => 'vacature_lister',
            'label'      => __('Vacature lister', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_vacature_lister_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'vacature_lister_heading',
                    'type'          => 'text',
                    'default_value' => '',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_vacature_lister_posts_per_page',
                    'label'         => __('Vacatures per page', 'boozed'),
                    'name'          => 'vacature_lister_posts_per_page',
                    'type'          => 'number',
                    'default_value' => 10,
                    'min'           => 1,
                    'max'           => 50,
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_vacature_lister_cursor_text',
                    'label'         => __('Custom cursor text', 'boozed'),
                    'name'          => 'vacature_lister_cursor_text',
                    'type'          => 'text',
                    'default_value' => 'Bekijk vacature',
                    'instructions'  => __('Text shown inside the custom cursor when hovering a row.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
