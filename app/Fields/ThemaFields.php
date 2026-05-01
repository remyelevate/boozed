<?php

namespace App\Fields;

use App\Fields\Sections\PageHeader;
use App\Fields\Sections\ProjectFeaturedImage;
use App\Fields\Sections\TekstMedia;
use App\Fields\Sections\ProjectsSlider;
use App\Fields\Sections\ProductSlider;
use App\Fields\Sections\ImageCarousel;
use App\Fields\Sections\InstagramSlider;
use App\Fields\Sections\Spacer;
use App\Fields\Sections\YoutubeEmbed;
use App\Fields\Sections\Wysiwyg;

/**
 * ACF field group for the Thema CPT.
 * Template-style: only these section types are available; editors can add, remove, and reorder them.
 */
class ThemaFields
{
    public static function init(): void
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_thema',
            'title'                 => __("Thema content", 'boozed'),
            'fields'                => self::get_fields(),
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'thema',
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
                'key'           => 'field_boozed_thema_click_target',
                'label'         => __('Klikgedrag op thema lister', 'boozed'),
                'name'          => 'thema_click_target',
                'type'          => 'radio',
                'instructions'  => __("Kies of een klik op dit thema in de thema lister naar de detailpagina gaat of direct naar de thema-producten op de PLP.", 'boozed'),
                'choices'       => [
                    'detail'  => __('Ga naar thema detailpagina', 'boozed'),
                    'products' => __('Ga direct naar thema-producten (PLP)', 'boozed'),
                ],
                'default_value' => 'detail',
                'layout'        => 'vertical',
            ],
            [
                'key'               => 'field_boozed_thema_plp_tags',
                'label'             => __('PLP tags', 'boozed'),
                'name'              => 'thema_plp_tags',
                'type'              => 'taxonomy',
                'taxonomy'          => 'product_tag',
                'field_type'        => 'multi_select',
                'instructions'      => __('Selecteer 1 of meer product tags die gebruikt worden om naar de juiste PLP te linken.', 'boozed'),
                'allow_null'        => 0,
                'add_term'          => 0,
                'save_terms'        => 0,
                'load_terms'        => 0,
                'return_format'     => 'id',
                'multiple'          => 1,
                'required'          => 1,
                'conditional_logic' => [
                    [
                        [
                            'field'    => 'field_boozed_thema_click_target',
                            'operator' => '==',
                            'value'    => 'products',
                        ],
                    ],
                ],
                'wrapper'           => ['width' => '100'],
            ],
            [
                'key'           => 'field_boozed_thema_sections',
                'label'         => __('Sections', 'boozed'),
                'name'          => 'sections',
                'type'          => 'flexible_content',
                'button_label'  => __('Add section', 'boozed'),
                'instructions'  => __("Build this Thema page with the template sections below. Add, remove or reorder as needed.", 'boozed'),
                'layouts'       => boozed_filter_sections_by_visibility([
                    'layout_boozed_page_header'       => PageHeader::get(),
                    'layout_boozed_project_featured_image' => ProjectFeaturedImage::get(),
                    'layout_boozed_tekst_media'       => TekstMedia::get(),
                    'layout_boozed_projects_slider'   => ProjectsSlider::get(),
                    'layout_boozed_product_slider'   => ProductSlider::get(),
                    'layout_boozed_image_carousel' => ImageCarousel::get(),
                    'layout_boozed_instagram_slider' => InstagramSlider::get(),
                    'layout_boozed_spacer'           => Spacer::get(),
                    'layout_boozed_youtube_embed'    => YoutubeEmbed::get(),
                    'layout_boozed_wysiwyg'          => Wysiwyg::get(),
                ]),
            ],
        ];
    }
}
