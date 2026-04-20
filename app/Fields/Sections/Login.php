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
                [
                    'key'           => 'field_boozed_login_intro',
                    'label'         => __('Intro text', 'boozed'),
                    'name'          => 'login_intro',
                    'type'          => 'text',
                    'default_value' => 'Log in met je accountgegevens.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_login_forgot_password_page',
                    'label'         => __('Forgot password page', 'boozed'),
                    'name'          => 'login_forgot_password_page',
                    'type'          => 'page_link',
                    'post_type'     => ['page'],
                    'allow_null'    => 1,
                    'multiple'      => 0,
                    'instructions'  => __('Optional: choose where "Wachtwoord vergeten?" should link.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
