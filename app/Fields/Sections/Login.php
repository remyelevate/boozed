<?php

namespace App\Fields\Sections;

class Login
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_login',
            'name'       => 'login',
            'label'      => __('Login', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_login_image',
                    'label'         => __('Left column image', 'boozed'),
                    'name'          => 'login_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => __('Image shown on the left (e.g. person with decorative background).', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_login_heading',
                    'label'         => __('Form heading', 'boozed'),
                    'name'          => 'login_heading',
                    'type'          => 'text',
                    'default_value' => 'Inloggen',
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
