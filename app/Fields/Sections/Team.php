<?php

namespace App\Fields\Sections;

class Team
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_team',
            'name'       => 'team',
            'label'      => __('Team', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_team_background',
                    'label'         => __('Background', 'boozed'),
                    'name'          => 'team_background',
                    'type'          => 'radio',
                    'choices'       => [
                        'gray'  => __('Gray', 'boozed'),
                        'white' => __('White', 'boozed'),
                    ],
                    'default_value' => 'gray',
                    'layout'        => 'horizontal',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'          => 'field_boozed_team_members',
                    'label'        => __('Team members', 'boozed'),
                    'name'         => 'team_members',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => __('Add team member', 'boozed'),
                    'collapsed'    => 'field_boozed_team_member_name',
                    'sub_fields'   => [
                        [
                            'key'           => 'field_boozed_team_member_photo',
                            'label'         => __('Photo', 'boozed'),
                            'name'          => 'photo',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'medium',
                        ],
                        [
                            'key'   => 'field_boozed_team_member_name',
                            'label' => __('Name', 'boozed'),
                            'name'  => 'name',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_boozed_team_member_role',
                            'label' => __('Role', 'boozed'),
                            'name'  => 'role',
                            'type'  => 'text',
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
