<?php

namespace App\Fields\Sections;

/**
 * Vacature features section: displays taxonomy terms (locatie, uren, niveau, team, dienstverband)
 * with icons from Global Settings → Vacatures. No per-section configuration needed.
 */
class VacatureFeatures
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_vacature_features',
            'name'       => 'vacature_features',
            'label'      => 'Vacature features',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'     => 'field_boozed_vacature_features_instructions',
                    'label'   => '',
                    'name'    => '',
                    'type'    => 'message',
                    'message' => __('This section automatically displays the vacature\'s taxonomy terms (Locatie, Uren, Niveau, Team, Dienstverband) with icons configured in Global Settings → Vacatures.', 'boozed'),
                    'wrapper' => ['width' => '100'],
                ],
            ],
        ];
    }
}
