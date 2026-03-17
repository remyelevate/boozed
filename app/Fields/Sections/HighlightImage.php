<?php

namespace App\Fields\Sections;

/**
 * Highlight image section: full-width background image with Lottie arrow
 * and content div (icon + label + optional URL) on left or right.
 */
class HighlightImage
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_highlight_image',
            'name'       => 'highlight_image',
            'label'      => __('Highlight image', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_highlight_image_background',
                    'label'         => __('Background image', 'boozed'),
                    'name'          => 'highlight_image_background',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'required'      => 1,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_highlight_image_position',
                    'label'         => __('Arrow & content position', 'boozed'),
                    'name'          => 'highlight_image_position',
                    'type'          => 'button_group',
                    'choices'       => [
                        'left'  => __('Left', 'boozed'),
                        'right' => __('Right', 'boozed'),
                    ],
                    'default_value' => 'right',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_highlight_image_icon',
                    'label'         => __('Content: Icon', 'boozed'),
                    'name'          => 'highlight_image_icon',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'thumbnail',
                    'wrapper'       => ['width' => '33'],
                ],
                [
                    'key'         => 'field_boozed_highlight_image_label',
                    'label'       => __('Content: Label', 'boozed'),
                    'name'        => 'highlight_image_label',
                    'type'        => 'text',
                    'placeholder' => 'Kom langs in ons 2000m² grote werkplaats',
                    'wrapper'     => ['width' => '33'],
                ],
                [
                    'key'     => 'field_boozed_highlight_image_url',
                    'label'   => __('Content: URL (optional)', 'boozed'),
                    'name'    => 'highlight_image_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '33'],
                ],
            ],
        ];
    }
}
