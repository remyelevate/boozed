<?php

namespace App\Fields\Sections;

class Brands
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_brands',
            'name'       => 'brands',
            'label'      => 'Brands',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_brands_title',
                    'label'   => 'Title',
                    'name'    => 'brands_title',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_brands_button_label',
                    'label'   => 'Button text',
                    'name'    => 'brands_button_label',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_brands_button_url',
                    'label'   => 'Button URL',
                    'name'    => 'brands_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'        => 'field_boozed_brands_items',
                    'label'      => 'Brands',
                    'name'       => 'brands_items',
                    'type'       => 'repeater',
                    'layout'     => 'table',
                    'button_label' => 'Add brand',
                    'sub_fields' => [
                        [
                            'key'           => 'field_boozed_brands_item_image',
                            'label'         => 'Image',
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'medium',
                        ],
                        [
                            'key'           => 'field_boozed_brands_item_url',
                            'label'         => 'URL',
                            'name'          => 'url',
                            'type'          => 'link',
                            'return_format' => 'url',
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
