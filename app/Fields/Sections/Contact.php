<?php

namespace App\Fields\Sections;

class Contact
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_contact',
            'name'       => 'contact',
            'label'      => __('Contact', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_contact_background_image',
                    'label'         => __('Background image', 'boozed'),
                    'name'          => 'contact_background_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_contact_heading',
                    'label'         => __('Form heading', 'boozed'),
                    'name'          => 'contact_heading',
                    'type'          => 'text',
                    'default_value' => 'Hoe kunnen we je helpen?',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_contact_form_shortcode',
                    'label'         => __('Contact Form 7 shortcode', 'boozed'),
                    'name'          => 'contact_form_shortcode',
                    'type'          => 'text',
                    'instructions'  => __('Paste the shortcode e.g. [contact-form-7 id="123" title="Contact"]. Use placeholders (no labels) and add the consent checkbox in the CF7 form – see theme docs or CONTACT-FORM-CF7-MARKUP.md.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_contact_spot_icon',
                    'label'         => __('Arrow target: Icon', 'boozed'),
                    'name'          => 'contact_spot_icon',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'thumbnail',
                    'wrapper'       => ['width' => '33'],
                ],
                [
                    'key'           => 'field_boozed_contact_spot_label',
                    'label'         => __('Arrow target: Label', 'boozed'),
                    'name'          => 'contact_spot_label',
                    'type'          => 'text',
                    'placeholder'   => 'Ons creatieve kantoor in Delft',
                    'wrapper'       => ['width' => '33'],
                ],
                [
                    'key'           => 'field_boozed_contact_spot_url',
                    'label'         => __('Arrow target: URL (optional)', 'boozed'),
                    'name'          => 'contact_spot_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper'       => ['width' => '33'],
                ],
                [
                    'key'          => 'field_boozed_contact_ticker_items',
                    'label'        => __('Ticker items', 'boozed'),
                    'name'         => 'contact_ticker_items',
                    'type'         => 'repeater',
                    'layout'       => 'table',
                    'button_label' => __('Add item', 'boozed'),
                    'collapsed'    => 'field_boozed_contact_ticker_item_text',
                    'sub_fields'   => [
                        [
                            'key'         => 'field_boozed_contact_ticker_item_text',
                            'label'       => __('Text', 'boozed'),
                            'name'        => 'contact_ticker_item_text',
                            'type'        => 'text',
                            'placeholder' => 'Schieweg 64, 2627 AN Delft',
                        ],
                        [
                            'key'   => 'field_boozed_contact_ticker_item_url',
                            'label' => __('URL (optional)', 'boozed'),
                            'name'  => 'contact_ticker_item_url',
                            'type'          => 'link',
                            'return_format' => 'url',
                        ],
                    ],
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
