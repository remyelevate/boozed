<?php

namespace App\Fields\Sections;

class PageHeader
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_page_header',
            'name'       => 'page_header',
            'label'      => 'Page header',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_page_header_background',
                    'label'         => 'Background',
                    'name'          => 'page_header_background',
                    'type'          => 'radio',
                    'choices'       => [
                        'light' => 'Light',
                        'dark'  => 'Dark',
                    ],
                    'default_value' => 'light',
                    'layout'        => 'horizontal',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_page_header_content_type',
                    'label'         => 'Content type',
                    'name'          => 'page_header_content_type',
                    'type'          => 'radio',
                    'choices'       => [
                        'content_and_ctas' => 'Content + CTAs',
                        'columns'          => 'Two description columns',
                    ],
                    'default_value' => 'content_and_ctas',
                    'layout'        => 'horizontal',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_page_header_title_top_spacing',
                    'label'         => 'Space above title',
                    'name'          => 'page_header_title_top_spacing',
                    'type'          => 'radio',
                    'choices'       => [
                        'min' => 'Minimum (header + 24 px)',
                        '50'  => 'Header + 24 px + 50 px',
                        '150' => 'Header + 24 px + 150 px',
                    ],
                    'default_value' => 'min',
                    'layout'        => 'horizontal',
                    'instructions'  => 'The minimum option clears the fixed site header plus 24 px. The other options add more space below that.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_page_header_title',
                    'label'         => 'Title',
                    'name'          => 'page_header_title',
                    'type'          => 'text',
                    'default_value' => '',
                    'wrapper'       => ['width' => '100'],
                ],
                // --- Content + CTAs fields ---
                [
                    'key'           => 'field_boozed_page_header_content',
                    'label'         => 'Content',
                    'name'          => 'page_header_content',
                    'type'          => 'wysiwyg',
                    'tabs'          => 'all',
                    'toolbar'       => 'basic',
                    'media_upload'  => 0,
                    'delay'         => 0,
                    'wrapper'       => ['width' => '100'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'content_and_ctas']],
                    ],
                ],
                [
                    'key'     => 'field_boozed_page_header_primary_label',
                    'label'   => 'Primary CTA label',
                    'name'    => 'page_header_primary_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'content_and_ctas']],
                    ],
                ],
                [
                    'key'     => 'field_boozed_page_header_primary_url',
                    'label'   => 'Primary CTA link',
                    'name'    => 'page_header_primary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'instructions'  => 'On a vacature page: choose this vacature’s URL and add anchor #solliciteren to open the application modal (e.g. Direct Solliciteren).',
                    'wrapper' => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'content_and_ctas']],
                    ],
                ],
                [
                    'key'     => 'field_boozed_page_header_secondary_label',
                    'label'   => 'Secondary link label',
                    'name'    => 'page_header_secondary_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'content_and_ctas']],
                    ],
                ],
                [
                    'key'     => 'field_boozed_page_header_secondary_url',
                    'label'   => 'Secondary link URL',
                    'name'    => 'page_header_secondary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'content_and_ctas']],
                    ],
                ],
                // --- Two columns fields ---
                [
                    'key'           => 'field_boozed_page_header_description_left',
                    'label'         => 'Description (left column)',
                    'name'          => 'page_header_description_left',
                    'type'          => 'wysiwyg',
                    'tabs'          => 'all',
                    'toolbar'       => 'basic',
                    'media_upload'  => 0,
                    'wrapper'       => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'columns']],
                    ],
                ],
                [
                    'key'           => 'field_boozed_page_header_description_right',
                    'label'         => 'Description (right column)',
                    'name'          => 'page_header_description_right',
                    'type'          => 'wysiwyg',
                    'tabs'          => 'all',
                    'toolbar'       => 'basic',
                    'media_upload'  => 0,
                    'wrapper'       => ['width' => '50'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_page_header_content_type', 'operator' => '==', 'value' => 'columns']],
                    ],
                ],
            ],
        ];
    }
}
