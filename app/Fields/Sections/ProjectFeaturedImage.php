<?php

namespace App\Fields\Sections;

/**
 * Layout for banner image section.
 * Intended for the project CPT flexible section builder. Toggle between post featured image or a custom image.
 */
class ProjectFeaturedImage
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_project_featured_image',
            'name'       => 'project_featured_image',
            'label'      => __('Banner image', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_banner_use_featured',
                    'label'         => __('Image source', 'boozed'),
                    'name'          => 'banner_use_featured',
                    'type'          => 'true_false',
                    'message'       => __('Use featured image', 'boozed'),
                    'default_value' => 1,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_banner_image',
                    'label'         => __('Custom image', 'boozed'),
                    'name'          => 'banner_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => __('Shown when "Use featured image" is off. Full container width, 800px tall on desktop.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                    'conditional_logic' => [
                        [
                            ['field' => 'field_boozed_banner_use_featured', 'operator' => '==', 'value' => '0'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
