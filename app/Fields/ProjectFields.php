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
use App\Fields\Sections\PageHeader;
use App\Fields\Sections\Marquee;
use App\Fields\Sections\ProjectShowcase;
use App\Fields\Sections\ProjectFeaturedImage;
use App\Fields\Sections\ProjectHours;
use App\Fields\Sections\ColleagueTestimonial;
use App\Fields\Sections\Features;
use App\Fields\Sections\ImageCarousel;
use App\Fields\Sections\HoverItems;
use App\Fields\Sections\SearchBanner;
use App\Fields\Sections\Steps;
use App\Fields\Sections\IntakeProcess;
use App\Fields\Sections\Faq;
use App\Fields\Sections\MapBanner;
use App\Fields\Sections\YoutubeEmbed;
use App\Fields\Sections\Wysiwyg;

class ProjectFields
{
    public static function init(): void
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_project',
            'title'                 => __('Project content', 'boozed'),
            'fields'                => self::get_fields(),
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'project',
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
                'key'           => 'field_boozed_project_short_description',
                'label'         => __('Short description / tagline', 'boozed'),
                'name'          => 'short_description',
                'type'          => 'textarea',
                'rows'          => 3,
                'instructions'  => __('Shown on the project listing and under the title. Optional.', 'boozed'),
                'wrapper'       => [ 'width' => '100' ],
            ],
            [
                'key'         => 'field_boozed_project_location',
                'label'       => __('Location', 'boozed'),
                'name'        => 'location',
                'type'        => 'text',
                'placeholder' => 'e.g. De Fabrique, Utrecht',
                'wrapper'     => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_boozed_project_sections',
                'label'         => __('Sections', 'boozed'),
                'name'          => 'sections',
                'type'          => 'flexible_content',
                'button_label'  => __('Add section', 'boozed'),
                'instructions'  => __('Build the project page with sections. Same sections as flexible pages.', 'boozed'),
                'layouts'       => boozed_filter_sections_by_visibility([
                    'layout_boozed_tekst'             => Tekst::get(),
                    'layout_boozed_tekst_media'       => TekstMedia::get(),
                    'layout_boozed_cta'               => Cta::get(),
                    'layout_boozed_hero'              => Hero::get(),
                    'layout_boozed_projects_slider'   => ProjectsSlider::get(),
                    'layout_boozed_projects_lister'   => ProjectsLister::get(),
                    'layout_boozed_blog_lister'       => BlogLister::get(),
                    'layout_boozed_brands'            => Brands::get(),
                    'layout_boozed_bcorp'             => Bcorp::get(),
                    'layout_boozed_page_header'       => PageHeader::get(),
                    'layout_boozed_marquee'           => Marquee::get(),
                    'layout_boozed_project_showcase'  => ProjectShowcase::get(),
                    'layout_boozed_project_featured_image' => ProjectFeaturedImage::get(),
                    'layout_boozed_project_hours'         => ProjectHours::get(),
                    'layout_boozed_colleague_testimonial' => ColleagueTestimonial::get(),
                    'layout_boozed_features' => Features::get(),
                    'layout_boozed_hover_items' => HoverItems::get(),
                    'layout_boozed_search_banner' => SearchBanner::get(),
                    'layout_boozed_steps' => Steps::get(),
                    'layout_boozed_intake_process' => IntakeProcess::get(),
                    'layout_boozed_faq' => Faq::get(),
                    'layout_boozed_image_carousel' => ImageCarousel::get(),
                    'layout_boozed_map_banner' => MapBanner::get(),
                    'layout_boozed_youtube_embed' => YoutubeEmbed::get(),
                    'layout_boozed_wysiwyg' => Wysiwyg::get(),
                ]),
            ],
        ];
    }
}
