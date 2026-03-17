<?php

namespace App\Fields;

class GlobalSettings
{
    public static function init()
    {
        self::register_header_fields();
        self::register_business_fields();
        self::register_footer_fields();
        self::register_vacature_fields();
        self::register_pdp_fields();
        self::load_header_menu_choices();
        self::load_footer_menu_choices();
    }

    protected static function register_header_fields()
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_global_settings_header',
            'title'                 => __('Header', 'boozed'),
            'fields'                => [
                [
                    'key'   => 'field_boozed_header_main_tab',
                    'label' => __('Main navigation', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_header_menu',
                    'label'         => __('Primary menu', 'boozed'),
                    'name'          => 'header_menu',
                    'type'          => 'select',
                    'choices'       => [],
                    'default_value' => '',
                    'allow_null'    => 1,
                    'return_format' => 'value',
                    'instructions'  => __('Choose which WordPress menu to show in the header.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_header_cta_text',
                    'label'         => __('CTA button text', 'boozed'),
                    'name'          => 'header_cta_text',
                    'type'          => 'text',
                    'default_value' => __('Offerte aanvragen', 'boozed'),
                    'placeholder'   => __('e.g. Offerte aanvragen', 'boozed'),
                ],
                [
                    'key'   => 'field_boozed_header_mega_menu_tab',
                    'label' => __('Mega menu', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'        => 'field_boozed_header_mega_menu',
                    'label'      => __('Mega menu items', 'boozed'),
                    'name'       => 'header_mega_menu',
                    'type'       => 'repeater',
                    'layout'     => 'block',
                    'min'        => 0,
                    'max'        => 0,
                    'sub_fields' => [
                        [
                            'key'   => 'field_boozed_header_mega_menu_label',
                            'label' => __('Label', 'boozed'),
                            'name'  => 'label',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_boozed_header_mega_menu_description',
                            'label' => __('Description', 'boozed'),
                            'name'  => 'description',
                            'type'  => 'textarea',
                            'rows'  => 2,
                        ],
                        [
                            'key'           => 'field_boozed_header_mega_menu_url',
                            'label'         => __('URL', 'boozed'),
                            'name'          => 'url',
                            'type'          => 'link',
                            'return_format' => 'url',
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'header',
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

    protected static function register_business_fields()
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_global_settings_business',
            'title'                 => __('Business', 'boozed'),
            'fields'                => [
                [
                    'key'         => 'field_boozed_business_phone',
                    'label'       => __('Phone number', 'boozed'),
                    'name'        => 'business_phone',
                    'type'        => 'text',
                    'placeholder' => '+31 (0) 15 38 07 515',
                    'instructions' => __('Main contact number shown in the header.', 'boozed'),
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'business',
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

    protected static function register_vacature_fields()
    {
        $taxonomies = [
            'locatie'       => __('Locatie', 'boozed'),
            'uren'          => __('Uren', 'boozed'),
            'niveau'        => __('Niveau', 'boozed'),
            'team'          => __('Team', 'boozed'),
            'dienstverband' => __('Dienstverband', 'boozed'),
        ];

        $fields = [
            [
                'key'     => 'field_boozed_vacature_icons_instructions',
                'label'   => '',
                'name'    => '',
                'type'    => 'message',
                'message' => __('Set a default icon for each vacature taxonomy. These icons are shown in the "Vacature features" section on vacature detail pages.', 'boozed'),
                'wrapper' => ['width' => '100'],
            ],
        ];

        foreach ($taxonomies as $slug => $label) {
            $fields[] = [
                'key'           => 'field_boozed_vacature_icon_' . $slug,
                'label'         => sprintf(__('Icon: %s', 'boozed'), $label),
                'name'          => 'vacature_icon_' . $slug,
                'type'          => 'image',
                'return_format' => 'id',
                'preview_size'  => 'thumbnail',
                'instructions'  => __('50×50 px, white/light outline for dark background.', 'boozed'),
            ];
        }

        acf_add_local_field_group([
            'key'                   => 'group_boozed_global_settings_vacatures',
            'title'                 => __('Vacature feature icons', 'boozed'),
            'fields'                => $fields,
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'vacatures',
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

    protected static function register_pdp_fields()
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_global_settings_pdp',
            'title'                 => __('PDP (Productpagina)', 'boozed'),
            'fields'                => [
                [
                    'key'   => 'field_boozed_pdp_urls_tab',
                    'label' => __('URLs', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_pdp_registration_url',
                    'label'         => __('Registratie-URL (account aanmaken)', 'boozed'),
                    'name'          => 'pdp_registration_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'placeholder'   => home_url('/registreren'),
                ],
                [
                    'key'           => 'field_boozed_pdp_maatwerk_url',
                    'label'         => __('Maatwerk-URL', 'boozed'),
                    'name'          => 'pdp_maatwerk_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'placeholder'   => '#',
                ],
                [
                    'key'   => 'field_boozed_pdp_texts_tab',
                    'label' => __('Teksten', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_pdp_breadcrumb_verhuur',
                    'label'         => __('Breadcrumb: Verhuur', 'boozed'),
                    'name'          => 'pdp_breadcrumb_verhuur',
                    'type'          => 'text',
                    'default_value' => __('Verhuur', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_tab_beschrijving',
                    'label'         => __('Tab: Beschrijving', 'boozed'),
                    'name'          => 'pdp_tab_beschrijving',
                    'type'          => 'text',
                    'default_value' => __('Beschrijving', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_tab_extra',
                    'label'         => __('Tab: Extra informatie', 'boozed'),
                    'name'          => 'pdp_tab_extra',
                    'type'          => 'text',
                    'default_value' => __('Extra informatie', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_geen_beschrijving',
                    'label'         => __('Geen beschrijving (fallback)', 'boozed'),
                    'name'          => 'pdp_geen_beschrijving',
                    'type'          => 'text',
                    'default_value' => __('Geen beschrijving.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_geen_extra_info',
                    'label'         => __('Geen extra info (fallback)', 'boozed'),
                    'name'          => 'pdp_geen_extra_info',
                    'type'          => 'text',
                    'default_value' => __('Geen extra informatie beschikbaar.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_meer_over_product',
                    'label'         => __('Heading: Meer over dit product', 'boozed'),
                    'name'          => 'pdp_meer_over_product',
                    'type'          => 'text',
                    'default_value' => __('Meer over dit product', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_gerelateerde',
                    'label'         => __('Heading: Gerelateerde producten', 'boozed'),
                    'name'          => 'pdp_gerelateerde',
                    'type'          => 'text',
                    'default_value' => __('Gerelateerde producten', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_related_link_text',
                    'label'         => __('Gerelateerde producten: linktekst', 'boozed'),
                    'name'          => 'pdp_related_link_text',
                    'type'          => 'text',
                    'default_value' => __('Bekijken >', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_benieuwd_prijs',
                    'label'         => __('CTA: Benieuwd naar de prijs?', 'boozed'),
                    'name'          => 'pdp_benieuwd_prijs',
                    'type'          => 'text',
                    'default_value' => __('Benieuwd naar de prijs?', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_cta_account',
                    'label'         => __('CTA-knop: Maak een account aan', 'boozed'),
                    'name'          => 'pdp_cta_account',
                    'type'          => 'text',
                    'default_value' => __('Maak een account aan >', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_usp_aria',
                    'label'         => __('USPs: aria-label', 'boozed'),
                    'name'          => 'pdp_usp_aria',
                    'type'          => 'text',
                    'default_value' => __('Voordelen', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_usp_1',
                    'label'         => __('USP 1', 'boozed'),
                    'name'          => 'pdp_usp_1',
                    'type'          => 'text',
                    'default_value' => __('Alles voor je event onder één dak', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_usp_2',
                    'label'         => __('USP 2', 'boozed'),
                    'name'          => 'pdp_usp_2',
                    'type'          => 'text',
                    'default_value' => __('Duurzaam & impact-gedreven', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_usp_3',
                    'label'         => __('USP 3', 'boozed'),
                    'name'          => 'pdp_usp_3',
                    'type'          => 'text',
                    'default_value' => __('Het grootste assortiment van Nederland', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_product_info_heading',
                    'label'         => __('Heading: Product informatie', 'boozed'),
                    'name'          => 'pdp_product_info_heading',
                    'type'          => 'text',
                    'default_value' => __('Product informatie', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_label_afmetingen',
                    'label'         => __('Productinfo: Afmetingen', 'boozed'),
                    'name'          => 'pdp_label_afmetingen',
                    'type'          => 'text',
                    'default_value' => __('Afmetingen', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_label_gewicht',
                    'label'         => __('Productinfo: Gewicht', 'boozed'),
                    'name'          => 'pdp_label_gewicht',
                    'type'          => 'text',
                    'default_value' => __('Gewicht', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_label_sku',
                    'label'         => __('Productinfo: SKU', 'boozed'),
                    'name'          => 'pdp_label_sku',
                    'type'          => 'text',
                    'default_value' => __('SKU', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_categorieen',
                    'label'         => __('Label: Categorieën', 'boozed'),
                    'name'          => 'pdp_categorieen',
                    'type'          => 'text',
                    'default_value' => __('Categorieën', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_tags',
                    'label'         => __('Label: Tags', 'boozed'),
                    'name'          => 'pdp_tags',
                    'type'          => 'text',
                    'default_value' => __('Tags', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_maatwerk_image',
                    'label'         => __('Maatwerk-blok: afbeelding', 'boozed'),
                    'name'          => 'pdp_maatwerk_image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'instructions'  => __('Afbeelding links in het Maatwerk-blok op de productpagina.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_maatwerk_heading',
                    'label'         => __('Maatwerk-blok: titel', 'boozed'),
                    'name'          => 'pdp_maatwerk_heading',
                    'type'          => 'text',
                    'default_value' => __('Kun je niet vinden wat je zoekt in ons verhuurassortiment?', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_maatwerk_text',
                    'label'         => __('Maatwerk-blok: tekst', 'boozed'),
                    'name'          => 'pdp_maatwerk_text',
                    'type'          => 'textarea',
                    'rows'          => 2,
                    'default_value' => __('Geen zorgen. We maken het op maat, passen het aan of halen het voor je op de juiste plek.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_pdp_maatwerk_btn',
                    'label'         => __('Maatwerk-blok: knoptekst', 'boozed'),
                    'name'          => 'pdp_maatwerk_btn',
                    'type'          => 'text',
                    'default_value' => __('Meer over maatwerk', 'boozed'),
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'pdp',
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

    protected static function load_header_menu_choices()
    {
        add_filter('acf/load_field/name=header_menu', function ($field) {
            $menus = wp_get_nav_menus();
            $field['choices'] = [ '' => __('— Select menu —', 'boozed') ];
            foreach ($menus as $menu) {
                $field['choices'][ (string) $menu->term_id ] = $menu->name;
            }
            return $field;
        });
    }

    protected static function register_footer_fields()
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_global_settings_footer',
            'title'                 => __('Footer', 'boozed'),
            'fields'                => [
                [
                    'key'           => 'field_boozed_footer_menu',
                    'label'         => __('Footer menu', 'boozed'),
                    'name'          => 'footer_menu',
                    'type'          => 'select',
                    'choices'       => [],
                    'default_value' => '',
                    'allow_null'    => 1,
                    'return_format' => 'value',
                    'instructions'  => __('Choose which WordPress menu to show in the footer.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_footer_tagline',
                    'label'         => __('Tagline', 'boozed'),
                    'name'          => 'footer_tagline',
                    'type'          => 'text',
                    'default_value' => __('Experience creators', 'boozed'),
                    'placeholder'   => __('e.g. Experience creators', 'boozed'),
                ],
                [
                    'key'   => 'field_boozed_footer_contact_tab',
                    'label' => __('Contact', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'         => 'field_boozed_footer_company',
                    'label'       => __('Company name', 'boozed'),
                    'name'        => 'footer_company',
                    'type'        => 'text',
                    'placeholder' => get_bloginfo('name'),
                ],
                [
                    'key'         => 'field_boozed_footer_address',
                    'label'       => __('Address', 'boozed'),
                    'name'        => 'footer_address',
                    'type'        => 'textarea',
                    'rows'        => 3,
                    'placeholder' => "Schieweg 64\n2627 AN Delft",
                ],
                [
                    'key'         => 'field_boozed_footer_phone',
                    'label'       => __('Phone', 'boozed'),
                    'name'        => 'footer_phone',
                    'type'        => 'text',
                    'placeholder' => '+31 (0) 15 38 07 515',
                    'instructions' => __('Leave empty to use the number from Business settings.', 'boozed'),
                ],
                [
                    'key'         => 'field_boozed_footer_email',
                    'label'       => __('Email', 'boozed'),
                    'name'        => 'footer_email',
                    'type'        => 'email',
                    'placeholder' => 'info@boozed.nl',
                ],
                [
                    'key'   => 'field_boozed_footer_bottom_tab',
                    'label' => __('Bottom bar', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_footer_copyright',
                    'label'         => __('Copyright text', 'boozed'),
                    'name'          => 'footer_copyright',
                    'type'          => 'text',
                    'default_value' => '© ' . gmdate('Y') . ' ' . get_bloginfo('name') . ' | ' . __('Experience Creators', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_footer_privacy_url',
                    'label'         => __('Privacy statement URL', 'boozed'),
                    'name'          => 'footer_privacy_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                ],
                [
                    'key'           => 'field_boozed_footer_terms_url',
                    'label'         => __('Terms and conditions URL', 'boozed'),
                    'name'          => 'footer_terms_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                ],
                [
                    'key'           => 'field_boozed_footer_facebook_url',
                    'label'         => __('Facebook URL', 'boozed'),
                    'name'          => 'footer_facebook_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                ],
                [
                    'key'           => 'field_boozed_footer_instagram_url',
                    'label'         => __('Instagram URL', 'boozed'),
                    'name'          => 'footer_instagram_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                ],
                [
                    'key'   => 'field_boozed_footer_cta_tab',
                    'label' => __('CTA Banner', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_footer_cta_enabled',
                    'label'         => __('Show CTA banner', 'boozed'),
                    'name'          => 'footer_cta_enabled',
                    'type'          => 'true_false',
                    'default_value' => 1,
                    'ui'            => 1,
                    'instructions'  => __('Display a call-to-action banner above the footer content.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_footer_cta_title',
                    'label'         => __('CTA title', 'boozed'),
                    'name'          => 'footer_cta_title',
                    'type'          => 'text',
                    'default_value' => __('Klaar voor jullie volgende experience?', 'boozed'),
                    'placeholder'   => __('e.g. Klaar voor jullie volgende experience?', 'boozed'),
                    'conditional_logic' => [[['field' => 'field_boozed_footer_cta_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'           => 'field_boozed_footer_cta_button_text',
                    'label'         => __('CTA button text', 'boozed'),
                    'name'          => 'footer_cta_button_text',
                    'type'          => 'text',
                    'default_value' => __('Neem contact op', 'boozed'),
                    'placeholder'   => __('e.g. Neem contact op', 'boozed'),
                    'conditional_logic' => [[['field' => 'field_boozed_footer_cta_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'               => 'field_boozed_footer_cta_button_url',
                    'label'             => __('CTA button URL', 'boozed'),
                    'name'              => 'footer_cta_button_url',
                    'type'              => 'link',
                    'return_format'     => 'url',
                    'placeholder'       => 'https://',
                    'conditional_logic' => [[['field' => 'field_boozed_footer_cta_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'           => 'field_boozed_footer_cta_secondary_text',
                    'label'         => __('Secondary button text', 'boozed'),
                    'name'          => 'footer_cta_secondary_text',
                    'type'          => 'text',
                    'default_value' => __('Meer over onze werkwijze', 'boozed'),
                    'placeholder'   => __('e.g. Meer over onze werkwijze', 'boozed'),
                    'conditional_logic' => [[['field' => 'field_boozed_footer_cta_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'               => 'field_boozed_footer_cta_secondary_url',
                    'label'             => __('Secondary button URL', 'boozed'),
                    'name'              => 'footer_cta_secondary_url',
                    'type'              => 'link',
                    'return_format'     => 'url',
                    'placeholder'       => 'https://',
                    'conditional_logic' => [[['field' => 'field_boozed_footer_cta_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'   => 'field_boozed_footer_newsletter_tab',
                    'label' => __('Newsletter', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_footer_newsletter_enabled',
                    'label'         => __('Show newsletter section', 'boozed'),
                    'name'          => 'footer_newsletter_enabled',
                    'type'          => 'true_false',
                    'default_value' => 1,
                    'ui'            => 1,
                    'instructions'  => __('Display a newsletter signup section in the footer.', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_footer_newsletter_title',
                    'label'         => __('Newsletter title', 'boozed'),
                    'name'          => 'footer_newsletter_title',
                    'type'          => 'text',
                    'default_value' => __('Blijf op de hoogte', 'boozed'),
                    'placeholder'   => __('e.g. Blijf op de hoogte', 'boozed'),
                    'conditional_logic' => [[['field' => 'field_boozed_footer_newsletter_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'           => 'field_boozed_footer_newsletter_description',
                    'label'         => __('Newsletter description', 'boozed'),
                    'name'          => 'footer_newsletter_description',
                    'type'          => 'textarea',
                    'rows'          => 2,
                    'placeholder'   => __('Optional short description', 'boozed'),
                    'conditional_logic' => [[['field' => 'field_boozed_footer_newsletter_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'           => 'field_boozed_footer_newsletter_shortcode',
                    'label'         => __('Newsletter form shortcode', 'boozed'),
                    'name'          => 'footer_newsletter_shortcode',
                    'type'          => 'text',
                    'placeholder'   => __('e.g. [mc4wp_form id="123"]', 'boozed'),
                    'instructions'   => __('Paste a shortcode from your newsletter plugin (e.g. Mailchimp, WPForms). Leave empty for a basic email form.', 'boozed'),
                    'conditional_logic' => [[['field' => 'field_boozed_footer_newsletter_enabled', 'operator' => '==', 'value' => '1']]],
                ],
                [
                    'key'   => 'field_boozed_footer_ticker_tab',
                    'label' => __('Ticker bar', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'        => 'field_boozed_footer_ticker_items',
                    'label'      => __('Ticker items', 'boozed'),
                    'name'       => 'footer_ticker_items',
                    'type'       => 'repeater',
                    'layout'     => 'table',
                    'button_label' => __('Add item', 'boozed'),
                    'instructions' => __('Items shown in the marquee bar between footer and newsletter. Each item is coloured Indigo, White or Coral in sequence.', 'boozed'),
                    'sub_fields' => [
                        [
                            'key'   => 'field_boozed_footer_ticker_item_text',
                            'label' => __('Text', 'boozed'),
                            'name'  => 'text',
                            'type'  => 'text',
                            'placeholder' => __('e.g. verhuur', 'boozed'),
                        ],
                    ],
                ],
                [
                    'key'   => 'field_boozed_footer_badges_tab',
                    'label' => __('Right column images', 'boozed'),
                    'name'  => '',
                    'type'  => 'tab',
                ],
                [
                    'key'           => 'field_boozed_footer_image_1',
                    'label'         => __('Image 1', 'boozed'),
                    'name'          => 'footer_image_1',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'instructions'  => __('e.g. Certified B Corporation logo', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_footer_image_1_url',
                    'label'         => __('Image 1 link URL', 'boozed'),
                    'name'          => 'footer_image_1_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'placeholder'   => 'https://',
                ],
                [
                    'key'           => 'field_boozed_footer_image_2',
                    'label'         => __('Image 2', 'boozed'),
                    'name'          => 'footer_image_2',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'instructions'  => __('e.g. EVENTEX INDEX Top 100 badge', 'boozed'),
                ],
                [
                    'key'           => 'field_boozed_footer_image_2_url',
                    'label'         => __('Image 2 link URL', 'boozed'),
                    'name'          => 'footer_image_2_url',
                    'type'          => 'link',
                    'return_format' => 'url',
                    'placeholder'   => 'https://',
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'footer',
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

    protected static function load_footer_menu_choices()
    {
        add_filter('acf/load_field/name=footer_menu', function ($field) {
            $menus = wp_get_nav_menus();
            $field['choices'] = [ '' => __('— Select menu —', 'boozed') ];
            foreach ($menus as $menu) {
                $field['choices'][ (string) $menu->term_id ] = $menu->name;
            }
            return $field;
        });
    }
}
