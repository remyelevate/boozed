<?php

/**
 * Section visibility configuration.
 *
 * Control which ACF flexible sections appear in the admin dropdown and on the frontend.
 * Set to false to hide a section from both the editor and the website.
 *
 * Use case: Hide sections for client meetings when scope was sold at fewer hours
 * than the full implementation (e.g. 120h sold but full site already built).
 *
 * Layout keys must match the keys used in PageSections, ProjectFields, VacatureFields, ThemaFields.
 * Set to true = visible, false = hidden.
 *
 * Example – hide for client meeting:
 *   'layout_boozed_offerte_aanvraag' => false,
 *   'layout_boozed_request_account' => false,
 */
return [
    // Core content
    'layout_boozed_post_content'           => true,
    'layout_boozed_tekst'                  => true,
    'layout_boozed_tekst_media'            => true,
    'layout_boozed_cta'                    => true,
    'layout_boozed_hero'                   => true,
    'layout_boozed_page_header'            => true,
    'layout_boozed_spacer'                 => true,

    // Projects
    'layout_boozed_projects_slider'        => true,
    'layout_boozed_projects_lister'        => true,
    'layout_boozed_project_showcase'       => false,
    'layout_boozed_project_featured_image' => true,
    'layout_boozed_project_hours'          => true,

    // Content & listings
    'layout_boozed_blog_lister'            => true,
    'layout_boozed_news_lister'            => true,
    'layout_boozed_brands'                 => true,
    'layout_boozed_bcorp'                  => true,
    'layout_boozed_theme_lister'           => true,

    // Marketing / conversion
    'layout_boozed_thank_you'              => true,
    'layout_boozed_marquee'                => true,
    'layout_boozed_colleague_testimonial'  => true,
    'layout_boozed_features'               => true,
    'layout_boozed_hover_items'            => true,
    'layout_boozed_search_banner'          => true,
    'layout_boozed_product_slider'         => true,
    'layout_boozed_product_lister'         => true,
    'layout_boozed_instagram_slider'       => true,

    // Process / forms
    'layout_boozed_steps'                  => true,
    'layout_boozed_intake_process'         => true,
    'layout_boozed_faq'                    => true,
    'layout_boozed_workday'                => true,
    'layout_boozed_vacature_features'      => true,
    'layout_boozed_vacature_lister'        => true,
    'layout_boozed_vacature_content'       => true,
    'layout_boozed_contact'                => true,
    'layout_boozed_login'                 => true,
    'layout_boozed_request_account'        => true,
    'layout_boozed_offerte_aanvraag'       => true,

    // Media & misc
    'layout_boozed_image_carousel'         => true,
    'layout_boozed_highlight_image'        => true,
    'layout_boozed_map_banner'             => true,
    'layout_boozed_youtube_embed'          => true,
];
