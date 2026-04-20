<?php

namespace App\Fields\Sections;

class ResetPassword
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_reset_password',
            'name'       => 'reset_password',
            'label'      => __('Reset password', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_reset_password_image',
                    'label'         => __('Left column image', 'boozed'),
                    'name'          => 'reset_password_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => __('Image shown on the left for reset password.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_reset_password_heading',
                    'label'         => __('Form heading', 'boozed'),
                    'name'          => 'reset_password_heading',
                    'type'          => 'text',
                    'default_value' => 'Wachtwoord resetten',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_reset_password_intro',
                    'label'         => __('Intro text', 'boozed'),
                    'name'          => 'reset_password_intro',
                    'type'          => 'text',
                    'default_value' => 'Kies een nieuw wachtwoord voor je account.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_reset_password_login_page',
                    'label'         => __('Login page', 'boozed'),
                    'name'          => 'reset_password_login_page',
                    'type'          => 'page_link',
                    'post_type'     => ['page'],
                    'allow_null'    => 1,
                    'multiple'      => 0,
                    'instructions'  => __('Optional: choose where to send users after successful reset.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_reset_password_forgot_page',
                    'label'         => __('Forgot password page', 'boozed'),
                    'name'          => 'reset_password_forgot_page',
                    'type'          => 'page_link',
                    'post_type'     => ['page'],
                    'allow_null'    => 1,
                    'multiple'      => 0,
                    'instructions'  => __('Optional: choose where "Nieuwe resetlink aanvragen" should link.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
