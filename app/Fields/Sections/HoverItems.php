<?php

namespace App\Fields\Sections;

class HoverItems
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_hover_items',
            'name'       => 'hover_items',
            'label'      => 'Hover items (image on hover)',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_hover_items_intro_heading',
                    'label'   => 'Intro heading',
                    'name'    => 'hover_items_intro_heading',
                    'type'    => 'text',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_hover_items_intro_body',
                    'label'   => 'Intro body',
                    'name'    => 'hover_items_intro_body',
                    'type'    => 'textarea',
                    'rows'    => 4,
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_hover_items_default_image',
                    'label'         => 'Default image',
                    'name'          => 'hover_items_default_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => 'Shown when no item is hovered.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'          => 'field_boozed_hover_items_cursor_label',
                    'label'        => 'Cursor label',
                    'name'         => 'hover_items_cursor_label',
                    'type'        => 'text',
                    'placeholder'  => 'Lees meer',
                    'instructions' => 'Label shown in the custom cursor (e.g. "Lees meer"). Used for translation.',
                    'wrapper'      => ['width' => '50'],
                ],
                [
                    'key'          => 'field_boozed_hover_items_items',
                    'label'        => 'Items',
                    'name'         => 'hover_items_items',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add item',
                    'max'          => 6,
                    'instructions' => 'Maximum 6 items.',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_boozed_hover_items_item_label',
                            'label' => 'Label',
                            'name'  => 'label',
                            'type'  => 'text',
                        ],
                        [
                            'key'           => 'field_boozed_hover_items_item_image',
                            'label'         => 'Image',
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'large',
                            'instructions'  => 'Shown on the right when this item is hovered.',
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
