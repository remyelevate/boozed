<?php

namespace App\Fields;

use App\Fields\Sections\Tekst;
use App\Fields\Sections\TekstMedia;
use App\Fields\Sections\Cta;
use App\Fields\Sections\Hero;
use App\Fields\Sections\ProjectsSlider;
use App\Fields\Sections\ProjectsLister;
use App\Fields\Sections\BlogLister;
use App\Fields\Sections\NewsLister;
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
use App\Fields\Sections\ProductLister;
use App\Fields\Sections\ImageCarousel;
use App\Fields\Sections\InstagramSlider;
use App\Fields\Sections\Steps;
use App\Fields\Sections\IntakeProcess;
use App\Fields\Sections\Faq;
use App\Fields\Sections\Workday;
use App\Fields\Sections\VacatureFeatures;
use App\Fields\Sections\VacatureLister;
use App\Fields\Sections\Contact;
use App\Fields\Sections\RequestAccount;
use App\Fields\Sections\OfferteAanvraag;
use App\Fields\Sections\HighlightImage;
use App\Fields\Sections\Login;
use App\Fields\Sections\MapBanner;
use App\Fields\Sections\ThemeLister;
use App\Fields\Sections\Spacer;
use App\Fields\Sections\YoutubeEmbed;
use App\Fields\Sections\Team;

class PageSections
{
    public static function init()
    {
        /**
         * Page sections flexible content (same pattern as transfit).
         * To add a section: 1) Create App\Fields\Sections\YourSection::get(), 2) Register here, 3) Add resources/views/sections/your-section.php
         */
        acf_add_local_field_group([
            'key'                   => 'group_boozed_page_sections',
            'title'                 => 'Page sections',
            'fields'                => [
                [
                    'key'           => 'field_boozed_sections',
                    'label'         => 'Sections',
                    'name'          => 'sections',
                    'type'          => 'flexible_content',
                    'button_label'  => 'Add section',
                    'layouts'       => boozed_filter_sections_by_visibility([
                        'layout_boozed_tekst'           => Tekst::get(),
                        'layout_boozed_tekst_media'  => TekstMedia::get(),
                        'layout_boozed_cta'          => Cta::get(),
                        'layout_boozed_hero'         => Hero::get(),
                        'layout_boozed_projects_slider' => ProjectsSlider::get(),
                        'layout_boozed_projects_lister' => ProjectsLister::get(),
                        'layout_boozed_blog_lister' => BlogLister::get(),
                        'layout_boozed_news_lister' => NewsLister::get(),
                        'layout_boozed_brands' => Brands::get(),
                        'layout_boozed_bcorp' => Bcorp::get(),
                        'layout_boozed_thank_you' => ThankYou::get(),
                        'layout_boozed_page_header' => PageHeader::get(),
                        'layout_boozed_marquee' => Marquee::get(),
                        'layout_boozed_project_showcase' => ProjectShowcase::get(),
                        'layout_boozed_project_featured_image' => ProjectFeaturedImage::get(),
                        'layout_boozed_colleague_testimonial' => ColleagueTestimonial::get(),
                        'layout_boozed_features' => Features::get(),
                        'layout_boozed_hover_items' => HoverItems::get(),
                        'layout_boozed_search_banner' => SearchBanner::get(),
                        'layout_boozed_product_slider' => ProductSlider::get(),
                        'layout_boozed_product_lister' => ProductLister::get(),
                        'layout_boozed_image_carousel' => ImageCarousel::get(),
                        'layout_boozed_instagram_slider' => InstagramSlider::get(),
                        'layout_boozed_steps' => Steps::get(),
                        'layout_boozed_intake_process' => IntakeProcess::get(),
                        'layout_boozed_faq' => Faq::get(),
                        'layout_boozed_workday' => Workday::get(),
                        'layout_boozed_vacature_features' => VacatureFeatures::get(),
                        'layout_boozed_vacature_lister' => VacatureLister::get(),
                        'layout_boozed_contact' => Contact::get(),
                        'layout_boozed_login' => Login::get(),
                        'layout_boozed_request_account' => RequestAccount::get(),
                        'layout_boozed_offerte_aanvraag' => OfferteAanvraag::get(),
                        'layout_boozed_highlight_image' => HighlightImage::get(),
                        'layout_boozed_map_banner' => MapBanner::get(),
                        'layout_boozed_theme_lister' => ThemeLister::get(),
                        'layout_boozed_team' => Team::get(),
                        'layout_boozed_spacer' => Spacer::get(),
                        'layout_boozed_youtube_embed' => YoutubeEmbed::get(),
                    ]),
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'page',
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
}
