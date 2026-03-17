<?php

namespace App\Fields\Sections;

class ProjectShowcase
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_project_showcase',
            'name'       => 'project_showcase',
            'label'      => 'Project showcase',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_project_showcase_title',
                    'label'   => 'Title',
                    'name'    => 'project_showcase_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_project_showcase_image_1',
                    'label'         => 'Image 1',
                    'name'          => 'project_showcase_image_1',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_project_showcase_image_2',
                    'label'         => 'Image 2',
                    'name'          => 'project_showcase_image_2',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_project_showcase_caption',
                    'label'   => 'Caption',
                    'name'    => 'project_showcase_caption',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_project_showcase_description',
                    'label'   => 'Description',
                    'name'    => 'project_showcase_description',
                    'type'    => 'wysiwyg',
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
