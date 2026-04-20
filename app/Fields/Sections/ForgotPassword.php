<?php

namespace App\Fields\Sections;

class ForgotPassword
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_forgot_password',
            'name'       => 'forgot_password',
            'label'      => __('Forgot password', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_forgot_password_image',
                    'label'         => __('Left column image', 'boozed'),
                    'name'          => 'forgot_password_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => __('Image shown on the left for forgot password.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_forgot_password_heading',
                    'label'         => __('Form heading', 'boozed'),
                    'name'          => 'forgot_password_heading',
                    'type'          => 'text',
                    'default_value' => 'Wachtwoord vergeten',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_forgot_password_intro',
                    'label'         => __('Intro text', 'boozed'),
                    'name'          => 'forgot_password_intro',
                    'type'          => 'text',
                    'default_value' => 'Vul je gebruikersnaam of e-mailadres in. We sturen je een link om je wachtwoord te resetten.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_forgot_password_login_page',
                    'label'         => __('Back to login page', 'boozed'),
                    'name'          => 'forgot_password_login_page',
                    'type'          => 'page_link',
                    'post_type'     => ['page'],
                    'allow_null'    => 1,
                    'multiple'      => 0,
                    'instructions'  => __('Optional: choose where "Terug naar inloggen" should link.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_forgot_password_reset_page',
                    'label'         => __('Reset password page', 'boozed'),
                    'name'          => 'forgot_password_reset_page',
                    'type'          => 'page_link',
                    'post_type'     => ['page'],
                    'allow_null'    => 1,
                    'multiple'      => 0,
                    'instructions'  => __('Optional: choose where reset links in the email should point.', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
