<?php

namespace App\Fields\Sections;

class TekstMedia
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_tekst_media',
            'name'       => 'tekst_media',
            'label'      => 'Text + Media',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_tekst_media_caption',
                    'label'         => 'Large caption',
                    'name'          => 'tekst_media_caption',
                    'type'          => 'text',
                    'instructions'  => 'Displayed as large headline. If it extends beyond the screen, a ticker/marquee effect is applied.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_tekst_media_position',
                    'label'         => 'Content position',
                    'name'          => 'tekst_media_position',
                    'type'          => 'radio',
                    'choices'       => [
                        'left'  => 'Media left, text right',
                        'right' => 'Media right, text left',
                    ],
                    'default_value' => 'left',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_tekst_media_remove_top_padding',
                    'label'         => 'Remove top padding',
                    'name'          => 'tekst_media_remove_top_padding',
                    'type'          => 'true_false',
                    'ui'            => 1,
                    'default_value' => 0,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_tekst_media_image_ratio',
                    'label'         => 'Image ratio',
                    'name'          => 'tekst_media_image_ratio',
                    'type'          => 'radio',
                    'choices'       => [
                        'portrait'  => 'Portrait',
                        'landscape' => 'Landscape',
                    ],
                    'default_value' => 'portrait',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_tekst_media_type',
                    'label'         => 'Media type',
                    'name'          => 'tekst_media_type',
                    'type'          => 'radio',
                    'choices'       => [
                        'image' => 'Image',
                        'video' => 'Video',
                    ],
                    'default_value' => 'image',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_tekst_media_image',
                    'label'         => 'Image',
                    'name'          => 'tekst_media_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_tekst_media_type', 'operator' => '==', 'value' => 'image']],
                    ],
                ],
                [
                    'key'           => 'field_boozed_tekst_media_video',
                    'label'         => 'Video',
                    'name'          => 'tekst_media_video',
                    'type'          => 'file',
                    'return_format' => 'url',
                    'mime_types'    => 'mp4,webm',
                    'wrapper'       => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_tekst_media_type', 'operator' => '==', 'value' => 'video']],
                    ],
                ],
                [
                    'key'     => 'field_boozed_tekst_media_content',
                    'label'   => 'Content',
                    'name'    => 'tekst_media_content',
                    'type'    => 'wysiwyg',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_tekst_media_primary_label',
                    'label'   => 'Primary button text',
                    'name'    => 'tekst_media_primary_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_tekst_media_primary_url',
                    'label'   => 'Primary button URL',
                    'name'    => 'tekst_media_primary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_tekst_media_secondary_label',
                    'label'   => 'Secondary button text',
                    'name'    => 'tekst_media_secondary_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_tekst_media_secondary_url',
                    'label'   => 'Secondary button URL',
                    'name'    => 'tekst_media_secondary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
            ],
        ];
    }
}
