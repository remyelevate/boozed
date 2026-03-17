<?php

namespace App\Fields\Sections;

class RequestAccount
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_request_account',
            'name'       => 'request_account',
            'label'      => __('Request Account', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_request_account_image',
                    'label'         => __('Left column image', 'boozed'),
                    'name'          => 'request_account_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'instructions'  => __('Image shown on the left (e.g. person with abstract background).', 'boozed'),
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_request_account_heading',
                    'label'         => __('Form heading', 'boozed'),
                    'name'          => 'request_account_heading',
                    'type'          => 'text',
                    'default_value' => 'Vul je gegevens in',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_request_account_form_shortcode',
                    'label'         => __('Contact Form 7 shortcode', 'boozed'),
                    'name'          => 'request_account_form_shortcode',
                    'type'          => 'text',
                    'instructions'  => __('Paste the shortcode e.g. [contact-form-7 id="123" title="Request Account"]. Use REQUEST-ACCOUNT-CF7-MARKUP.md for the form template and thank-you redirect.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
