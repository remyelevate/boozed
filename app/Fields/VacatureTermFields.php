<?php

namespace App\Fields;

/**
 * ACF fields for vacature taxonomy terms (locatie, niveau, team, dienstverband).
 * Used by the Vacature features section to show an icon per term.
 */
class VacatureTermFields
{
    public static function init(): void
    {
        $taxonomies = [ 'locatie', 'uren', 'niveau', 'team', 'dienstverband' ];

        acf_add_local_field_group([
            'key'                   => 'group_boozed_vacature_term',
            'title'                 => __('Vacature feature icon', 'boozed'),
            'fields'                => [
                [
                    'key'           => 'field_boozed_vacature_term_icon',
                    'label'         => __('Icon', 'boozed'),
                    'name'          => 'vacature_feature_icon',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'thumbnail',
                    'instructions'  => __('Icon shown in the vacature features section (50×50 px). Use a white/light outline for the dark background.', 'boozed'),
                ],
            ],
            'location'              => array_map(function ($tax) {
                return [
                    [
                        'param'    => 'taxonomy',
                        'operator' => '==',
                        'value'    => $tax,
                    ],
                ];
            }, $taxonomies),
            'position'              => 'normal',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'active'                => true,
        ]);
    }
}
