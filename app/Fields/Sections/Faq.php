<?php

namespace App\Fields\Sections;

class Faq
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_faq',
            'name'       => 'faq',
            'label'      => 'FAQ',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_faq_title',
                    'label'   => 'Title',
                    'name'    => 'faq_title',
                    'type'    => 'text',
                    'default_value' => 'Veelgestelde vragen',
                    'wrapper' => ['width' => '100'],
                ],
                [
                    'key'          => 'field_boozed_faq_items',
                    'label'        => 'FAQ items',
                    'name'         => 'faq_items',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add question',
                    'collapsed'    => 'field_boozed_faq_item_question',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_boozed_faq_item_question',
                            'label' => 'Question',
                            'name'  => 'question',
                            'type'  => 'text',
                            'wrapper' => ['width' => '100'],
                        ],
                        [
                            'key'   => 'field_boozed_faq_item_answer',
                            'label' => 'Answer',
                            'name'  => 'answer',
                            'type'  => 'wysiwyg',
                            'tabs'  => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                            'delay' => 0,
                            'wrapper' => ['width' => '100'],
                        ],
                    ],
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
