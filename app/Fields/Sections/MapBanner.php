<?php

namespace App\Fields\Sections;

class MapBanner
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_map_banner',
            'name'       => 'map_banner',
            'label'      => __('Map banner', 'boozed'),
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_map_banner_title',
                    'label'         => __('Title (optional)', 'boozed'),
                    'name'          => 'map_banner_title',
                    'type'          => 'text',
                    'instructions'  => __('Shown above the map. Leave empty to hide.', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_map_banner_address',
                    'label'         => __('Address', 'boozed'),
                    'name'          => 'map_banner_address',
                    'type'          => 'text',
                    'default_value' => 'Schieweg 64, 2627 AN Delft, Netherlands',
                    'placeholder'   => 'Schieweg 64, 2627 AN Delft, Netherlands',
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_map_banner_zoom',
                    'label'         => __('Zoom level', 'boozed'),
                    'name'          => 'map_banner_zoom',
                    'type'          => 'number',
                    'default_value' => 16,
                    'min'           => 1,
                    'max'           => 19,
                    'step'          => 1,
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_map_banner_height',
                    'label'         => __('Map height (px)', 'boozed'),
                    'name'          => 'map_banner_height',
                    'type'          => 'number',
                    'default_value' => 400,
                    'min'           => 200,
                    'max'           => 800,
                    'step'          => 50,
                    'wrapper'       => ['width' => '50'],
                ],
            ],
        ];
    }
}
