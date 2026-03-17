<?php

namespace App\Fields\Sections;

class BlogLister
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_blog_lister',
            'name'       => 'blog_lister',
            'label'      => 'Blog lister',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_blog_lister_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'blog_lister_heading',
                    'type'          => 'text',
                    'default_value' => 'Achter de schermen & vooraan in trends',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_blog_lister_more_label',
                    'label'         => __('More link label', 'boozed'),
                    'name'          => 'blog_lister_more_label',
                    'type'          => 'text',
                    'default_value' => 'Meer inspiratie?',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_blog_lister_more_url',
                    'label'         => __('More link URL', 'boozed'),
                    'name'          => 'blog_lister_more_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_blog_lister_posts_per_page',
                    'label'         => __('Number of posts', 'boozed'),
                    'name'          => 'blog_lister_posts_per_page',
                    'type'          => 'number',
                    'default_value' => 8,
                    'min'           => 2,
                    'max'           => 20,
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
