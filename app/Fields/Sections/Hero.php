<?php

namespace App\Fields\Sections;

class Hero
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_hero',
            'name'       => 'hero',
            'label'      => 'Hero',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'         => 'field_boozed_hero_backdrop_type',
                    'label'       => 'Backdrop type',
                    'name'        => 'hero_backdrop_type',
                    'type'        => 'radio',
                    'choices'     => [
                        'image' => 'Image',
                        'video' => 'Video',
                    ],
                    'default_value' => 'image',
                    'wrapper'     => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_hero_background_image',
                    'label'         => 'Background image',
                    'name'          => 'hero_background_image',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'large',
                    'wrapper'       => ['width' => '50'],
                    'conditional_logic' => [
                        [
                            ['field' => 'field_boozed_hero_backdrop_type', 'operator' => '==', 'value' => 'image'],
                        ],
                    ],
                ],
                [
                    'key'           => 'field_boozed_hero_background_video',
                    'label'         => 'Background video',
                    'name'          => 'hero_background_video',
                    'type'          => 'file',
                    'return_format' => 'url',
                    'mime_types'    => 'mp4,webm',
                    'wrapper'       => ['width' => '50'],
                    'conditional_logic' => [
                        [
                            ['field' => 'field_boozed_hero_backdrop_type', 'operator' => '==', 'value' => 'video'],
                        ],
                    ],
                ],
                [
                    'key'           => 'field_boozed_hero_heading',
                    'label'         => 'Title',
                    'name'          => 'hero_heading',
                    'type'          => 'text',
                    'default_value' => 'Wij maken beleving tastbaar.',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'     => 'field_boozed_hero_primary_button_text',
                    'label'   => 'Primary CTA label',
                    'name'    => 'hero_primary_button_text',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_hero_primary_button_url',
                    'label'   => 'Primary CTA link',
                    'name'    => 'hero_primary_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_hero_secondary_button_text',
                    'label'   => 'Secondary CTA label',
                    'name'    => 'hero_secondary_button_text',
                    'type'    => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_hero_secondary_button_url',
                    'label'   => 'Secondary CTA link',
                    'name'    => 'hero_secondary_button_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'      => 'field_boozed_hero_cards_tab',
                    'label'    => 'Bottom cards (Experience, Rental, Fabrications)',
                    'name'     => '',
                    'type'     => 'tab',
                ],
                [
                    'key'     => 'field_boozed_hero_experience_url',
                    'label'   => 'Experience card link',
                    'name'    => 'hero_experience_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_hero_experience_description',
                    'label'         => 'Experience card description',
                    'name'          => 'hero_experience_description',
                    'type'          => 'textarea',
                    'rows'          => 3,
                    'default_value' => 'Een onvergetelijke merkbeleving neerzetten? We got you! Boozed vertaalt jouw idee om tot een experience die je voelt, ziet én onthoudt. Ontdek onze werkwijze',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_hero_experience_read_more',
                    'label'         => 'Experience "Read more" label',
                    'name'          => 'hero_experience_read_more',
                    'type'          => 'text',
                    'default_value' => 'Lees meer.',
                    'wrapper'       => ['width' => '33'],
                ],
                [
                    'key'           => 'field_boozed_hero_cursor_text',
                    'label'         => 'Custom cursor text',
                    'name'          => 'hero_cursor_text',
                    'type'          => 'text',
                    'default_value' => 'Lees meer.',
                    'instructions'  => 'Text shown inside the custom cursor blob when hovering the experience card.',
                    'wrapper'       => ['width' => '33'],
                ],
                [
                    'key'     => 'field_boozed_hero_rental_url',
                    'label'   => 'Rental card link',
                    'name'    => 'hero_rental_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_hero_rental_description',
                    'label'         => 'Rental card description',
                    'name'          => 'hero_rental_description',
                    'type'          => 'textarea',
                    'rows'          => 3,
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'     => 'field_boozed_hero_fabrications_url',
                    'label'   => 'Fabrications card link',
                    'name'    => 'hero_fabrications_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_hero_fabrications_description',
                    'label'         => 'Fabrications card description',
                    'name'          => 'hero_fabrications_description',
                    'type'          => 'textarea',
                    'rows'          => 3,
                    'default_value' => '',
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
