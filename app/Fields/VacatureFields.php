<?php

namespace App\Fields;

use App\Fields\Sections\Tekst;
use App\Fields\Sections\TekstMedia;
use App\Fields\Sections\Cta;
use App\Fields\Sections\Hero;
use App\Fields\Sections\ProjectsSlider;
use App\Fields\Sections\ProjectsLister;
use App\Fields\Sections\BlogLister;
use App\Fields\Sections\Brands;
use App\Fields\Sections\Bcorp;
use App\Fields\Sections\ThankYou;
use App\Fields\Sections\PageHeader;
use App\Fields\Sections\Marquee;
use App\Fields\Sections\ProjectShowcase;
use App\Fields\Sections\ProjectFeaturedImage;
use App\Fields\Sections\ColleagueTestimonial;
use App\Fields\Sections\Features;
use App\Fields\Sections\HoverItems;
use App\Fields\Sections\SearchBanner;
use App\Fields\Sections\ProductSlider;
use App\Fields\Sections\ImageCarousel;
use App\Fields\Sections\InstagramSlider;
use App\Fields\Sections\Steps;
use App\Fields\Sections\IntakeProcess;
use App\Fields\Sections\Faq;
use App\Fields\Sections\Workday;
use App\Fields\Sections\VacatureFeatures;
use App\Fields\Sections\VacatureContent;
use App\Fields\Sections\MapBanner;
use App\Fields\Sections\YoutubeEmbed;

class VacatureFields
{
    public static function init(): void
    {
        self::register_field_group();
    }

    private static function register_field_group(): void
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_vacature',
            'title'                 => __('Vacature content', 'boozed'),
            'fields'                => self::get_fields(),
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'vacature',
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
                'key'           => 'field_boozed_vacature_apply_url',
                'label'         => __('Apply URL', 'boozed'),
                'name'          => 'apply_url',
                'type'          => 'link',
                'return_format' => 'url',
                'instructions'  => __('Optional. Link for the sticky "Solliciteer" button. Leave empty to open the sollicitatie form on this page (same as linking to this page with #solliciteren). Use an external URL only if applications go elsewhere.', 'boozed'),
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_boozed_vacature_short_description',
                'label'         => __('Short description / intro', 'boozed'),
                'name'          => 'short_description',
                'type'          => 'textarea',
                'rows'          => 3,
                'instructions'  => __('Shown under the title on the vacature detail page. Optional.', 'boozed'),
                'wrapper'       => [ 'width' => '100' ],
            ],
            [
                'key'           => 'field_boozed_vacature_sections',
                'label'         => __('Sections', 'boozed'),
                'name'          => 'sections',
                'type'          => 'flexible_content',
                'button_label'  => __('Add section', 'boozed'),
                'instructions'  => __('Build the vacature detail page with sections. Same sections as flexible pages.', 'boozed'),
                'layouts'       => boozed_filter_sections_by_visibility([
                    'layout_boozed_tekst'                  => Tekst::get(),
                    'layout_boozed_tekst_media'            => TekstMedia::get(),
                    'layout_boozed_cta'                    => Cta::get(),
                    'layout_boozed_hero'                   => Hero::get(),
                    'layout_boozed_projects_slider'        => ProjectsSlider::get(),
                    'layout_boozed_projects_lister'       => ProjectsLister::get(),
                    'layout_boozed_blog_lister'            => BlogLister::get(),
                    'layout_boozed_brands'                 => Brands::get(),
                    'layout_boozed_bcorp'                  => Bcorp::get(),
                    'layout_boozed_thank_you'              => ThankYou::get(),
                    'layout_boozed_page_header'             => PageHeader::get(),
                    'layout_boozed_marquee'                => Marquee::get(),
                    'layout_boozed_project_showcase'       => ProjectShowcase::get(),
                    'layout_boozed_project_featured_image' => ProjectFeaturedImage::get(),
                    'layout_boozed_colleague_testimonial'  => ColleagueTestimonial::get(),
                    'layout_boozed_features'               => Features::get(),
                    'layout_boozed_hover_items'            => HoverItems::get(),
                    'layout_boozed_search_banner'          => SearchBanner::get(),
                    'layout_boozed_product_slider'         => ProductSlider::get(),
                    'layout_boozed_image_carousel'         => ImageCarousel::get(),
                    'layout_boozed_instagram_slider'       => InstagramSlider::get(),
                    'layout_boozed_steps'                  => Steps::get(),
                    'layout_boozed_intake_process'         => IntakeProcess::get(),
                    'layout_boozed_faq'                    => Faq::get(),
                    'layout_boozed_workday'               => Workday::get(),
                    'layout_boozed_vacature_features'     => VacatureFeatures::get(),
                    'layout_boozed_vacature_content'       => VacatureContent::get(),
                    'layout_boozed_map_banner'           => MapBanner::get(),
                    'layout_boozed_youtube_embed'       => YoutubeEmbed::get(),
                ]),
            ],
        ];
    }
}
