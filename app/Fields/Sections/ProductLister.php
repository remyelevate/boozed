<?php

namespace App\Fields\Sections;

class ProductLister
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_product_lister',
            'name'       => 'product_lister',
            'label'      => __('Product lister', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_product_lister_heading',
                    'label'         => __('Heading', 'boozed'),
                    'name'          => 'product_lister_heading',
                    'type'          => 'text',
                    'default_value' => __('Verhuur', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_search_url',
                    'label'         => __('Search bar URL', 'boozed'),
                    'name'          => 'product_lister_search_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'instructions'  => __('URL of the catalog/search page. Leave empty to use current page.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_search_placeholder',
                    'label'         => __('Search bar placeholder', 'boozed'),
                    'name'          => 'product_lister_search_placeholder',
                    'type'          => 'text',
                    'default_value' => __('Online catalogus (meer dan 2000 items)', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_quick_filters',
                    'label'         => __('Quick-select filters', 'boozed'),
                    'name'          => 'product_lister_quick_filters',
                    'type'          => 'taxonomy',
                    'taxonomy'      => 'product_cat',
                    'field_type'    => 'multi_select',
                    'allow_null'    => 1,
                    'return_format' => 'object',
                    'multiple'      => 1,
                    'instructions'  => __('Choose categories to display as quick-select filter buttons above the product grid.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_posts_per_page',
                    'label'         => __('Products per page', 'boozed'),
                    'name'          => 'product_lister_posts_per_page',
                    'type'          => 'number',
                    'min'           => 1,
                    'max'           => 48,
                    'default_value' => 12,
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_link_text',
                    'label'         => __('Product card link text', 'boozed'),
                    'name'          => 'product_lister_link_text',
                    'type'          => 'text',
                    'default_value' => '',
                    'instructions'  => __('e.g. Bekijken. Optional visible label below the title. Cards always link to the product page.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'       => 'field_boozed_product_lister_cta_tab',
                    'label'     => __('4th item (CTA block)', 'boozed'),
                    'name'      => '',
                    'type'      => 'tab',
                    'wrapper'   => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_cta_text',
                    'label'         => __('Main text', 'boozed'),
                    'name'          => 'product_lister_cta_text',
                    'type'          => 'textarea',
                    'rows'          => 2,
                    'default_value' => __('Om de prijzen te kunnen zien kun je een account bij ons aanvragen.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_cta_secondary_text',
                    'label'         => __('Secondary text', 'boozed'),
                    'name'          => 'product_lister_cta_secondary_text',
                    'type'          => 'textarea',
                    'rows'          => 2,
                    'default_value' => __('Heb je een vraag of wens je meer informatie over een product? We helpen je graag verder!', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_cta_btn1_label',
                    'label'         => __('Button 1 label', 'boozed'),
                    'name'          => 'product_lister_cta_btn1_label',
                    'type'          => 'text',
                    'default_value' => __('Account aanvragen', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_cta_btn1_url',
                    'label'         => __('Button 1 URL', 'boozed'),
                    'name'          => 'product_lister_cta_btn1_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_cta_btn2_label',
                    'label'         => __('Button 2 label', 'boozed'),
                    'name'          => 'product_lister_cta_btn2_label',
                    'type'          => 'text',
                    'default_value' => __('Contact opnemen', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_product_lister_cta_btn2_url',
                    'label'         => __('Button 2 URL', 'boozed'),
                    'name'          => 'product_lister_cta_btn2_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
