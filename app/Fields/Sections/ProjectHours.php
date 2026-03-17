<?php

namespace App\Fields\Sections;

/**
 * Layout for project hours / cijfers section.
 * Full-bleed section with optional left column (title, text, CTA) and right column of stat rows
 * with hover-reveal image. For the project CPT flexible section builder.
 */
class ProjectHours
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_project_hours',
            'name'       => 'project_hours',
            'label'      => __('Project hours / Cijfers', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_project_hours_heading',
                    'label'   => __('Heading', 'boozed'),
                    'name'    => 'project_hours_heading',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_project_hours_content',
                    'label'   => __('Content', 'boozed'),
                    'name'    => 'project_hours_content',
                    'type'    => 'wysiwyg',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_project_hours_cta_label',
                    'label'   => __('CTA button text', 'boozed'),
                    'name'    => 'project_hours_cta_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_project_hours_cta_url',
                    'label'   => __('CTA button URL', 'boozed'),
                    'name'    => 'project_hours_cta_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'         => 'field_boozed_project_hours_rows',
                    'label'       => __('Stat rows (max 3, for alignment with left panel)', 'boozed'),
                    'name'        => 'project_hours_rows',
                    'type'        => 'repeater',
                    'layout'      => 'block',
                    'min'         => 0,
                    'max'         => 3,
                    'button_label' => __('Add row', 'boozed'),
                    'sub_fields'  => [
                        [
                            'key'   => 'field_boozed_project_hours_row_number',
                            'label' => __('Number', 'boozed'),
                            'name'  => 'number',
                            'type'  => 'text',
                            'placeholder' => 'e.g. 6',
                        ],
                        [
                            'key'   => 'field_boozed_project_hours_row_label',
                            'label' => __('Label', 'boozed'),
                            'name'  => 'label',
                            'type'  => 'text',
                            'placeholder' => 'e.g. uur opbouw',
                        ],
                        [
                            'key'           => 'field_boozed_project_hours_row_image',
                            'label'         => __('Image (shown on row hover)', 'boozed'),
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'medium',
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
