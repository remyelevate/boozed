<?php

namespace App\Fields\Sections;

class Wysiwyg
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_wysiwyg',
            'name'       => 'wysiwyg',
            'label'      => 'WYSIWYG',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_wysiwyg_content',
                    'label'         => 'Content',
                    'name'          => 'wysiwyg_content',
                    'type'          => 'wysiwyg',
                    'tabs'          => 'all',
                    'toolbar'       => 'full',
                    'media_upload'  => 1,
                    'delay'         => 0,
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
