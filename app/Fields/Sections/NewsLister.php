<?php

namespace App\Fields\Sections;

class NewsLister
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_news_lister',
            'name'       => 'news_lister',
            'label'      => __('News lister', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_news_lister_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'news_lister_heading',
                    'type'          => 'text',
                    'default_value' => '',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_news_lister_posts_per_page',
                    'label'         => __('Posts per page', 'boozed'),
                    'name'          => 'news_lister_posts_per_page',
                    'type'          => 'number',
                    'default_value' => 9,
                    'min'           => 1,
                    'max'           => 50,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_news_lister_cursor_label',
                    'label'         => __('Cursor label (on card hover)', 'boozed'),
                    'name'          => 'news_lister_cursor_label',
                    'type'          => 'text',
                    'default_value' => __('Bericht lezen', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
