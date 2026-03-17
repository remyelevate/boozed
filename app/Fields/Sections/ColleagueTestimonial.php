<?php

namespace App\Fields\Sections;

class ColleagueTestimonial
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_colleague_testimonial',
            'name'       => 'colleague_testimonial',
            'label'      => 'Colleague testimonial',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_colleague_testimonial_label',
                    'label'         => __('Section label', 'boozed'),
                    'name'          => 'colleague_testimonial_label',
                    'type'          => 'text',
                    'default_value' => 'Boozed',
                    'instructions'  => __('Small label above the subtitle (e.g. "Boozed")', 'boozed'),
                    'wrapper'       => [ 'width' => '50' ],
                ],
                [
                    'key'           => 'field_boozed_colleague_testimonial_title',
                    'label'         => __('Section subtitle', 'boozed'),
                    'name'          => 'colleague_testimonial_title',
                    'type'          => 'text',
                    'instructions'  => __('e.g. "Wat zeggen onze medewerkers?"', 'boozed'),
                    'wrapper'       => [ 'width' => '50' ],
                ],
                [
                    'key'           => 'field_boozed_colleague_testimonial_count',
                    'label'         => __('Number of testimonials to show', 'boozed'),
                    'name'          => 'colleague_testimonial_count',
                    'type'          => 'number',
                    'min'           => 1,
                    'max'           => 20,
                    'default_value' => 4,
                    'wrapper'       => [ 'width' => '50' ],
                ],
                [
                    'key'           => 'field_boozed_colleague_testimonial_autoplay',
                    'label'         => __('Autoplay through testimonials', 'boozed'),
                    'name'          => 'colleague_testimonial_autoplay',
                    'type'          => 'true_false',
                    'default_value' => 1,
                    'ui'            => 1,
                    'wrapper'       => [ 'width' => '50' ],
                ],
                [
                    'key'           => 'field_boozed_colleague_testimonial_primary_label',
                    'label'         => __('Primary button text', 'boozed'),
                    'name'          => 'colleague_testimonial_primary_label',
                    'type'          => 'text',
                    'default_value' => 'Contact',
                    'wrapper'       => [ 'width' => '50' ],
                ],
                [
                    'key'     => 'field_boozed_colleague_testimonial_primary_url',
                    'label'   => __('Primary button URL', 'boozed'),
                    'name'    => 'colleague_testimonial_primary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => [ 'width' => '50' ],
                ],
                [
                    'key'           => 'field_boozed_colleague_testimonial_secondary_label',
                    'label'         => __('Secondary link text', 'boozed'),
                    'name'          => 'colleague_testimonial_secondary_label',
                    'type'          => 'text',
                    'default_value' => 'Meer over onze werkwijze',
                    'wrapper'       => [ 'width' => '50' ],
                ],
                [
                    'key'     => 'field_boozed_colleague_testimonial_secondary_url',
                    'label'   => __('Secondary link URL', 'boozed'),
                    'name'    => 'colleague_testimonial_secondary_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'wrapper' => [ 'width' => '50' ],
                ],
            ],
        ];
    }
}
