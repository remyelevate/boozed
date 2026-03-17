<?php

namespace App\Fields\Sections;

class ProjectsSlider
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_projects_slider',
            'name'       => 'projects_slider',
            'label'      => 'Projects slider',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_projects_slider_content_source',
                    'label'         => __('Content source', 'boozed'),
                    'name'          => 'projects_slider_content_source',
                    'type'          => 'radio',
                    'choices'       => [
                        'projects' => __('Dynamic projects', 'boozed'),
                        'custom'   => __('Custom content', 'boozed'),
                    ],
                    'default_value' => 'projects',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_projects_slider_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'projects_slider_heading',
                    'type'          => 'text',
                    'default_value' => 'Onze projecten waar we trots op zijn',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_projects_slider_body',
                    'label'         => __('Body text', 'boozed'),
                    'name'          => 'projects_slider_body',
                    'type'          => 'textarea',
                    'rows'          => 4,
                    'default_value' => 'Wij zijn creatieve architecten die jouw verhaal met impact tot leven brengen. Merken zichtbaar en tastbaar maken.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_projects_slider_button_label',
                    'label'         => __('View all button label', 'boozed'),
                    'name'          => 'projects_slider_button_label',
                    'type'          => 'text',
                    'default_value' => 'Bekijk al onze projecten',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_projects_slider_button_url',
                    'label'         => __('View all button URL', 'boozed'),
                    'name'          => 'projects_slider_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_projects_slider_cursor_text',
                    'label'         => __('Custom cursor text', 'boozed'),
                    'name'          => 'projects_slider_cursor_text',
                    'type'          => 'text',
                    'default_value' => 'Bekijk project',
                    'instructions'  => __('Text shown inside the custom cursor when hovering cards.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'             => 'field_boozed_projects_slider_items',
                    'label'           => __('Slider items', 'boozed'),
                    'name'            => 'projects_slider_items',
                    'type'            => 'repeater',
                    'layout'          => 'block',
                    'button_label'    => __('Add item', 'boozed'),
                    'conditional_logic' => [
                        [['field' => 'field_boozed_projects_slider_content_source', 'operator' => '==', 'value' => 'custom']],
                    ],
                    'sub_fields'      => [
                        [
                            'key'           => 'field_boozed_projects_slider_item_image',
                            'label'         => __('Image', 'boozed'),
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'medium',
                            'required'      => 1,
                        ],
                        [
                            'key'   => 'field_boozed_projects_slider_item_tagline',
                            'label' => __('Tagline', 'boozed'),
                            'name'  => 'tagline',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_boozed_projects_slider_item_title',
                            'label' => __('Title', 'boozed'),
                            'name'  => 'title',
                            'type'  => 'text',
                            'required' => 1,
                        ],
                        [
                            'key'   => 'field_boozed_projects_slider_item_link',
                            'label' => __('Link URL', 'boozed'),
                            'name'  => 'link',
                            'type'          => 'link',
                            'return_format' => 'url',
                        ],
                        [
                            'key'           => 'field_boozed_projects_slider_item_link_text',
                            'label'         => __('Link text', 'boozed'),
                            'name'          => 'link_text',
                            'type'          => 'text',
                            'instructions'  => __('e.g. Lees meer. Leave empty to not show a link.', 'boozed'),
                        ],
                    ],
                    'wrapper'         => ['width' => '100'],
                ],
            ],
        ];
    }
}
