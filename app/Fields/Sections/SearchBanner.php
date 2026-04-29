<?php

namespace App\Fields\Sections;

class SearchBanner
{
    public static function get()
    {
        return [
            'key'        => 'layout_boozed_search_banner',
            'name'       => 'search_banner',
            'label'      => 'Search banner',
            'display'    => 'block',
            'sub_fields' => [
                [
                    'key'           => 'field_boozed_search_banner_search_title',
                    'label'         => __('Search title (left)', 'boozed'),
                    'name'          => 'search_banner_search_title',
                    'type'          => 'text',
                    'default_value' => 'Waar ben je naar op zoek?',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_search_banner_placeholder',
                    'label'         => __('Search placeholder', 'boozed'),
                    'name'          => 'search_banner_placeholder',
                    'type'          => 'text',
                    'default_value' => 'Zoek naar meer dan 2000 items uit onze catalogus',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_search_banner_results_url',
                    'label'         => __('Search results page URL', 'boozed'),
                    'name'          => 'search_banner_results_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'instructions'  => __('Page where search results will be shown. Leave empty to use default (e.g. /assortiment/).', 'boozed'),
                    'wrapper'       => ['width' => '100'],
                ],
                [
                    'key'           => 'field_boozed_search_banner_right_title',
                    'label'         => __('Right column title', 'boozed'),
                    'name'          => 'search_banner_right_title',
                    'type'          => 'text',
                    'default_value' => 'Wat kun je huren?',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_boozed_search_banner_right_body',
                    'label'         => __('Right column body', 'boozed'),
                    'name'          => 'search_banner_right_body',
                    'type'          => 'textarea',
                    'rows'          => 5,
                    'default_value' => "Om je een indruk te geven van wat wij allemaal verhuren hebben wij onze productcategoriën hieronder voor je uitgewerkt. Weet je al wat je nodig hebt? Gebruik dan de zoekfunctie hiernaast. Wil je liever gelijk alle verhuurproducten bekijken? Klik dan hiernaast.",
                    'wrapper'       => ['width' => '100'],
                ],
            ],
        ];
    }
}
