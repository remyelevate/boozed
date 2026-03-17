<?php

namespace App\Fields\Sections;

class Bcorp
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_bcorp',
            'name'       => 'bcorp',
            'label'      => 'BCorp',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_bcorp_white_bg',
                    'label'         => 'White background',
                    'name'          => 'bcorp_white_background',
                    'type'          => 'true_false',
                    'message'       => 'Use white background (inverts logo and uses black text)',
                    'default_value' => 0,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_bcorp_image',
                    'label'         => 'Image',
                    'name'          => 'bcorp_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => 'BCorp logo or certification image (left column).',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_bcorp_title',
                    'label'   => 'Title',
                    'name'    => 'bcorp_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_bcorp_content',
                    'label'   => 'Content',
                    'name'    => 'bcorp_content',
                    'type'    => 'wysiwyg',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_bcorp_primary_label',
                    'label'   => 'Primary button text',
                    'name'    => 'bcorp_primary_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_bcorp_primary_url',
                    'label'   => 'Primary button URL',
                    'name'    => 'bcorp_primary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_bcorp_secondary_label',
                    'label'   => 'Secondary button text',
                    'name'    => 'bcorp_secondary_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_bcorp_secondary_url',
                    'label'   => 'Secondary button URL',
                    'name'    => 'bcorp_secondary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
            ],
        ];
    }
}
