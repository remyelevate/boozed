<?php

namespace App\Fields\Sections;

class Workday
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_workday',
            'name'       => 'workday',
            'label'      => __('Workday', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_workday_title',
                    'label'         => __('Title', 'boozed'),
                    'name'          => 'workday_title',
                    'type'          => 'text',
                    'default_value' => 'Zo ziet jouw dag eruit!',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_workday_intro',
                    'label'         => __('Intro', 'boozed'),
                    'name'          => 'workday_intro',
                    'type'          => 'textarea',
                    'rows'          => 4,
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'          => 'field_boozed_workday_slots',
                    'label'        => __('Time slots', 'boozed'),
                    'name'         => 'workday_slots',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => __('Add slot', 'boozed'),
                    'collapsed'    => 'field_boozed_workday_slot_label',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_boozed_workday_slot_label',
                            'label' => __('Time / label', 'boozed'),
                            'name'  => 'label',
                            'type'  => 'text',
                            'placeholder' => '09:00 - 12:00',
                        ],
                        [
                            'key'         => 'field_boozed_workday_slot_highlighted',
                            'label'       => __('Default active', 'boozed'),
                            'name'        => 'is_highlighted',
                            'type'        => 'true_false',
                            'ui'          => 1,
                            'default_value' => 0,
                            'instructions' => __('Show this slot as the initially active/highlighted slot.', 'boozed'),
                        ],
                        [
                            'key'          => 'field_boozed_workday_slot_content',
                            'label'        => __('Content (right panel)', 'boozed'),
                            'name'         => 'content',
                            'type'         => 'wysiwyg',
                            'tabs'         => 'all',
                            'toolbar'      => 'full',
                            'media_upload' => 0,
                            'delay'        => 1,
                            'instructions' => __('Shown in the dark right column when this slot is hovered / active.', 'boozed'),
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
