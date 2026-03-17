<?php

namespace App\Fields\Sections;

/**
 * Renders the main post content (the_content()) within the flexible sections.
 * Use this layout to place the blog body in a specific position among other sections.
 */
class PostContent
{
    public static function get()
    {
        return [
            'key'         => 'layout_boozed_post_content',
            'name'        => 'post_content',
            'label'       => __('Post content', 'boozed'),
            'display'     => 'block',
            'sub_fields'  => [
                [
                    'key'           => 'field_boozed_post_content_instructions',
                    'label'         => '',
                    'name'          => '',
                    'type'          => 'message',
                    'message'       => __('This block outputs the main post content (the editor content) at this position. Add it once to show the body text, or omit it to build the page entirely from sections.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
