<?php

namespace App\Fields\Sections;

class ThemeLister
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_theme_lister',
            'name'       => 'theme_lister',
            'label'      => __('Theme lister', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_theme_lister_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'theme_lister_heading',
                    'type'          => 'text',
                    'default_value' => __("Thema's", 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_theme_lister_more_label',
                    'label'         => __('More link label', 'boozed'),
                    'name'          => 'theme_lister_more_label',
                    'type'          => 'text',
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_theme_lister_more_url',
                    'label'         => __('More link URL', 'boozed'),
                    'name'          => 'theme_lister_more_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_theme_lister_posts_per_page',
                    'label'         => __('Number of themes', 'boozed'),
                    'name'          => 'theme_lister_posts_per_page',
                    'type'          => 'number',
                    'default_value' => 9,
                    'min'           => 1,
                    'max'           => 24,
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_theme_lister_cursor_text',
                    'label'         => __('Custom cursor text', 'boozed'),
                    'name'          => 'theme_lister_cursor_text',
                    'type'          => 'text',
                    'default_value' => __('Thema bekijken', 'boozed'),
                    'instructions'  => __('Text shown inside the custom cursor when hovering a card.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
