<?php

namespace App\Fields\Sections;

class InstagramSlider
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_instagram_slider',
            'name'       => 'instagram_slider',
            'label'      => __('Instagram slider', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_instagram_slider_cta_heading',
                    'label'         => __('CTA card heading', 'boozed'),
                    'name'          => 'instagram_slider_cta_heading',
                    'type'          => 'text',
                    'default_value' => 'Volg ons op Instagram',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_instagram_slider_cta_body',
                    'label'         => __('CTA card body text', 'boozed'),
                    'name'          => 'instagram_slider_cta_body',
                    'type'          => 'textarea',
                    'rows'          => 3,
                    'default_value' => 'Wij zijn creatieve architecten die jouw verhaal met impact tot leven brengen. Merken zichtbaar en tastbaar maken.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_instagram_slider_cta_url',
                    'label'         => __('Instagram profile URL', 'boozed'),
                    'name'          => 'instagram_slider_cta_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'instructions'  => __('Link for the CTA card (e.g. your Instagram profile).', 'boozed'),
                    'placeholder'   => 'https://instagram.com/yourhandle',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'             => 'field_boozed_instagram_slider_posts',
                    'label'           => __('Slider posts', 'boozed'),
                    'name'            => 'instagram_slider_posts',
                    'type'            => 'repeater',
                    'layout'          => 'block',
                    'button_label'   => __('Add post', 'boozed'),
                    'instructions'    => __('Add images to show as Instagram-style posts. For automatic feed, use a plugin like Smash Balloon Instagram Feed and place its shortcode in a separate section.', 'boozed'),
                    'sub_fields'      => [
                        [
                            'key'           => 'field_boozed_instagram_slider_post_image',
                            'label'         => __('Image', 'boozed'),
                            'name'          => 'image',
                            'type'          => 'image',
                            'return_format' => 'id',
                            'preview_size'  => 'medium',
                            'required'      => 1,
                        ],
                        [
                            'key'           => 'field_boozed_instagram_slider_post_caption',
                            'label'         => __('Caption / overlay text', 'boozed'),
                            'name'          => 'caption',
                            'type'          => 'textarea',
                            'rows'          => 2,
                            'instructions'  => __('Optional. Shown as overlay (e.g. job title, quote).', 'boozed'),
                        ],
                        [
                            'key'   => 'field_boozed_instagram_slider_post_link',
                            'label' => __('Link URL', 'boozed'),
                            'name'  => 'link',
                            'type'          => 'link',
                            'return_format' => 'url',
                        ],
                    ],
                    'wrapper'         => ['width' => '100'],
                ],
            ],
        ];
    }
}
