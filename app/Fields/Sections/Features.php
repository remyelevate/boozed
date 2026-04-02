<?php

namespace App\Fields\Sections;

class Features
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_features',
            'name'       => 'features',
            'label'      => 'Features',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_features_intro_heading',
                    'label'   => 'Intro heading',
                    'name'    => 'features_intro_heading',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_features_intro_body',
                    'label'         => 'Intro body',
                    'name'          => 'features_intro_body',
                    'type'          => 'wysiwyg',
                    'tabs'          => 'all',
                    'toolbar'       => 'full',
                    'media_upload'  => 0,
                    'delay'         => 0,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_features_default_image',
                    'label'         => 'Default image',
                    'name'          => 'features_default_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => 'Shown when no feature is hovered.',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_features_default_title',
                    'label'   => 'Default title',
                    'name'    => 'features_default_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_features_default_description',
                    'label'         => 'Default description',
                    'name'          => 'features_default_description',
                    'type'          => 'wysiwyg',
                    'tabs'          => 'all',
                    'toolbar'       => 'full',
                    'media_upload'  => 0,
                    'delay'         => 0,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'          => 'field_boozed_features_items',
                    'label'        => 'Features',
                    'name'         => 'features_items',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add feature',
                    'max'          => 4,
                    'instructions' => 'Maximum 4 features.',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_boozed_features_item_label',
                            'label' => 'Label',
                            'name'  => 'label',
                            'type'  => 'text',
                        ],
                        [
                            'key'           => 'field_boozed_features_item_image',
                            'label'         => 'Image',
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'large',
                            'instructions'  => 'Shown on the right when this feature is hovered.',
                        ],
                        [
                            'key'           => 'field_boozed_features_item_description',
                            'label'         => 'Description',
                            'name'          => 'description',
                            'type'          => 'wysiwyg',
                            'tabs'          => 'all',
                            'toolbar'       => 'full',
                            'media_upload'  => 0,
                            'delay'         => 1,
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
