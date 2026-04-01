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
                    'key'           => 'field_boozed_team_title',
                    'label'         => __('Title', 'boozed'),
                    'name'          => 'team_title',
                    'type'          => 'text',
                    'default_value' => __('Ons team', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_team_intro',
                    'label'         => __('Intro', 'boozed'),
                    'name'          => 'team_intro',
                    'type'          => 'textarea',
                    'rows'          => 3,
                    'wrapper'       => ['width' => '50'],
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
