<?php

namespace App\Fields;

/**
 * ACF field group for the Testimonial CPT.
 * Post title = internal name; ACF fields = display title + content; Featured image = photo.
 */
class TestimonialFields
{
    public static function init(): void
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_testimonial',
            'title'                 => __('Testimonial content', 'boozed'),
            'fields'                => self::get_fields(),
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'testimonial',
                    ],
                ],
            ],
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'active'                => true,
        ]);
    }

    protected static function get_fields(): array
    {
        return [
            [
                'key'           => 'field_boozed_testimonial_title',
                'label'         => __('Title', 'boozed'),
                'name'          => 'testimonial_title',
                'type'          => 'textarea',
                'rows'          => 3,
                'new_lines'     => 'br',
                'instructions'  => __('The headline displayed on the front end, e.g. Sifra, Eventmanager: "Ik mag elke dag meewerken aan de leukste en gekste creaties"', 'boozed'),
                'wrapper'       => [ 'width' => '100' ],
            ],
            [
                'key'           => 'field_boozed_testimonial_content',
                'label'         => __('Content', 'boozed'),
                'name'          => 'testimonial_content',
                'type'          => 'wysiwyg',
                'media_upload'  => 0,
                'tabs'          => 'all',
                'toolbar'       => 'basic',
                'instructions'  => __('The full testimonial quote text displayed next to the image.', 'boozed'),
                'wrapper'       => [ 'width' => '100' ],
            ],
        ];
    }
}
