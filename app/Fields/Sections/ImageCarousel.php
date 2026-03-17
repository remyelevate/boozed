<?php

namespace App\Fields\Sections;

/**
 * Image carousel section: horizontal scrollable gallery with custom cursor (arrows icon).
 */
class ImageCarousel
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_image_carousel',
            'name'       => 'image_carousel',
            'label'      => __('Image carousel', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'             => 'field_boozed_image_carousel_images',
                    'label'           => __('Images', 'boozed'),
                    'name'            => 'image_carousel_images',
                    'type'            => 'repeater',
                    'layout'          => 'block',
                    'button_label'   => __('Add image', 'boozed'),
                    'min'             => 1,
                    'sub_fields'      => [
                        [
                            'key'           => 'field_boozed_image_carousel_image',
                            'label'         => __('Image', 'boozed'),
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'medium',
                            'required'      => 1,
                        ],
                    ],
                    'wrapper'         => ['width' => '100'],
                ],
            ],
        ];
    }
}
