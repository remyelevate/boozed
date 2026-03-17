<?php

namespace App\Fields\Sections;

class ProductSlider
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_product_slider',
            'name'       => 'product_slider',
            'label'      => __('Product slider', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_product_slider_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'product_slider_heading',
                    'type'          => 'text',
                    'default_value' => __('Onze populaire items', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_search_url',
                    'label'         => __('Search bar URL', 'boozed'),
                    'name'          => 'product_slider_search_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'instructions'  => __('URL of the catalog/search page. Leave empty to hide the search bar.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_search_placeholder',
                    'label'         => __('Search bar placeholder', 'boozed'),
                    'name'          => 'product_slider_search_placeholder',
                    'type'          => 'text',
                    'default_value' => __('Online catalogus (meer dan 2000 producten)', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_button_label',
                    'label'         => __('View all button label', 'boozed'),
                    'name'          => 'product_slider_button_label',
                    'type'          => 'text',
                    'default_value' => __('Bekijk alle producten', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_button_url',
                    'label'         => __('View all button URL', 'boozed'),
                    'name'          => 'product_slider_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_link_text',
                    'label'         => __('Product card link text', 'boozed'),
                    'name'          => 'product_slider_link_text',
                    'type'          => 'text',
                    'default_value' => '',
                    'instructions'  => __('e.g. Bekijken. Optional visible label below the title. Cards always link to the product page.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_source',
                    'label'         => __('Which products to show', 'boozed'),
                    'name'          => 'product_slider_source',
                    'type'          => 'radio',
                    'choices'       => [
                        'tags'       => __('By tags', 'boozed'),
                        'collection' => __('By collection (category)', 'boozed'),
                        'manual'     => __('Select products manually', 'boozed'),
                    ],
                    'default_value' => 'manual',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'               => 'field_boozed_product_slider_tag_terms',
                    'label'             => __('Product tags', 'boozed'),
                    'name'              => 'product_slider_tag_terms',
                    'type'              => 'taxonomy',
                    'taxonomy'          => 'product_tag',
                    'field_type'        => 'multi_select',
                    'allow_null'        => 0,
                    'add_term'          => 0,
                    'save_terms'        => 0,
                    'load_terms'        => 0,
                    'return_format'     => 'id',
                    'multiple'          => 1,
                    'conditional_logic' => [
                        [['field' => 'field_boozed_product_slider_source', 'operator' => '==', 'value' => 'tags']],
                    ],
                    'wrapper'           => ['width' => '100'],
                ],
                [
                    'key'               => 'field_boozed_product_slider_collection_terms',
                    'label'             => __('Product collection (category)', 'boozed'),
                    'name'              => 'product_slider_collection_terms',
                    'type'              => 'taxonomy',
                    'taxonomy'          => 'product_cat',
                    'field_type'        => 'multi_select',
                    'allow_null'        => 0,
                    'add_term'          => 0,
                    'save_terms'        => 0,
                    'load_terms'        => 0,
                    'return_format'     => 'id',
                    'multiple'          => 1,
                    'conditional_logic' => [
                        [['field' => 'field_boozed_product_slider_source', 'operator' => '==', 'value' => 'collection']],
                    ],
                    'wrapper'           => ['width' => '100'],
                ],
                [
                    'key'               => 'field_boozed_product_slider_products',
                    'label'             => __('Products', 'boozed'),
                    'name'              => 'product_slider_products',
                    'type'              => 'relationship',
                    'post_type'         => ['product'],
                    'return_format'      => 'id',
                    'min'               => 0,
                    'max'               => 24,
                    'filters'           => ['search', 'post_type'],
                    'conditional_logic' => [
                        [['field' => 'field_boozed_product_slider_source', 'operator' => '==', 'value' => 'manual']],
                    ],
                    'wrapper'           => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_slider_limit',
                    'label'         => __('Maximum number of products (for tags/collection)', 'boozed'),
                    'name'          => 'product_slider_limit',
                    'type'          => 'number',
                    'min'           => 1,
                    'max'           => 48,
                    'default_value' => 12,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_boozed_product_slider_source',
                                'operator' => '!=',
                                'value' => 'manual',
                            ],
                        ],
                    ],
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
