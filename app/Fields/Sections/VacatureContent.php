<?php

namespace App\Fields\Sections;

class VacatureContent
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_vacature_content',
            'name'       => 'vacature_content',
            'label'      => __('Vacature content', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_vacature_content_title',
                    'label'   => __('Title (large ticker)', 'boozed'),
                    'name'    => 'vacature_content_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'          => 'field_boozed_vacature_content_perks',
                    'label'        => __('Perks', 'boozed'),
                    'name'         => 'vacature_content_perks',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => __('Add perk', 'boozed'),
                    'sub_fields'   => [
                        [
                            'key'           => 'field_boozed_vacature_content_perk_icon',
                            'label'         => __('Icon', 'boozed'),
                            'name'          => 'icon',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'thumbnail',
                        ],
                        [
                            'key'   => 'field_boozed_vacature_content_perk_title',
                            'label' => __('Title', 'boozed'),
                            'name'  => 'title',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_boozed_vacature_content_perk_content',
                            'label' => __('Content', 'boozed'),
                            'name'  => 'content',
                            'type'  => 'wysiwyg',
                            'tabs'  => 'all',
                            'toolbar' => 'basic',
                            'media_upload' => 0,
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_vacature_content_banner',
                    'label'         => __('Banner image', 'boozed'),
                    'name'          => 'vacature_content_banner',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_vacature_content_left_title',
                    'label'   => __('Left column title', 'boozed'),
                    'name'    => 'vacature_content_left_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_vacature_content_left_content',
                    'label'         => __('Left column content', 'boozed'),
                    'name'          => 'vacature_content_left_content',
                    'type'          => 'wysiwyg',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_vacature_content_right_title',
                    'label'   => __('Right column title', 'boozed'),
                    'name'    => 'vacature_content_right_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_vacature_content_right_content',
                    'label'         => __('Right column content', 'boozed'),
                    'name'          => 'vacature_content_right_content',
                    'type'          => 'wysiwyg',
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
