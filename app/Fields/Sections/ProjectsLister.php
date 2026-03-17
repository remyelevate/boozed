<?php

namespace App\Fields\Sections;

class ProjectsLister
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_projects_lister',
            'name'       => 'projects_lister',
            'label'      => 'Projects lister',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_projects_lister_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'projects_lister_heading',
                    'type'          => 'text',
                    'default_value' => '',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_projects_lister_posts_per_page',
                    'label'         => __('Projects per page', 'boozed'),
                    'name'          => 'projects_lister_posts_per_page',
                    'type'          => 'number',
                    'default_value' => 10,
                    'min'           => 1,
                    'max'           => 50,
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_projects_lister_cursor_text',
                    'label'         => __('Custom cursor text', 'boozed'),
                    'name'          => 'projects_lister_cursor_text',
                    'type'          => 'text',
                    'default_value' => 'Bekijk project',
                    'instructions'  => __('Text shown inside the custom cursor when hovering the featured image.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
