<?php

namespace App\Fields\Sections;

class Steps
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_steps',
            'name'       => 'steps',
            'label'      => 'Steps',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'          => 'field_boozed_steps_items',
                    'label'        => 'Steps',
                    'name'         => 'steps_items',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add step',
                    'max'          => 10,
                    'collapsed'    => 'field_boozed_steps_item_heading',
                    'instructions' => 'Maximum 10 steps. Each step has an image, eyebrow, heading, content and list items.',
                    'sub_fields'   => [
                        [
                            'key'           => 'field_boozed_steps_item_image',
                            'label'         => 'Image',
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'large',
                            'wrapper'       => ['width' => '100'],
                        ],
                        [
                            'key'   => 'field_boozed_steps_item_eyebrow',
                            'label' => 'Eyebrow',
                            'name'  => 'eyebrow',
                            'type'  => 'text',
                            'wrapper' => ['width' => '100'],
                        ],
                        [
                            'key'   => 'field_boozed_steps_item_heading',
                            'label' => 'Heading',
                            'name'  => 'heading',
                            'type'  => 'text',
                            'wrapper' => ['width' => '100'],
                        ],
                        [
                            'key'   => 'field_boozed_steps_item_content',
                            'label' => 'Content',
                            'name'  => 'content',
                            'type'  => 'textarea',
                            'rows'  => 4,
                            'wrapper' => ['width' => '100'],
                        ],
                        [
                            'key'          => 'field_boozed_steps_item_list_items',
                            'label'        => 'List items',
                            'name'         => 'list_items',
                            'type'         => 'repeater',
                            'layout'       => 'table',
                            'button_label' => 'Add item',
                            'sub_fields'   => [
                                [
                                    'key'   => 'field_boozed_steps_item_list_label',
                                    'label' => 'Label',
                                    'name'  => 'label',
                                    'type'  => 'text',
                                ],
                            ],
                            'wrapper' => ['width' => '100'],
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
