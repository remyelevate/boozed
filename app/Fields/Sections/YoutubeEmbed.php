<?php

namespace App\Fields\Sections;

/**
 * YouTube embed section: single video via URL (watch, youtu.be, or embed).
 */
class YoutubeEmbed
{
    public static function get()
    {
        return [
            'key'         => 'layout_boozed_youtube_embed',
            'name'        => 'youtube_embed',
            'label'       => __('YouTube embed', 'boozed'),
            'display'     => 'block',
            'sub_fields'  => [
                [
                    'key'         => 'field_boozed_youtube_embed_url',
                    'label'       => __('YouTube URL', 'boozed'),
                    'name'        => 'youtube_embed_url',
                    'type'        => 'link',
                    'return_format' => 'url',
                    'placeholder' => 'https://www.youtube.com/watch?v=... or https://youtu.be/...',
                    'required'    => 1,
                    'wrapper'     => ['width' => '100'],
                ],
                [
                    'key'         => 'field_boozed_youtube_embed_caption',
                    'label'       => __('Caption (optional)', 'boozed'),
                    'name'        => 'youtube_embed_caption',
                    'type'        => 'text',
                    'wrapper'     => ['width' => '100'],
                ],
            ],
        ];
    }
}
