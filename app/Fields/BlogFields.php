<?php

namespace App\Fields;

use App\Fields\Sections\PostContent;
use App\Fields\Sections\Tekst;
use App\Fields\Sections\TekstMedia;
use App\Fields\Sections\Cta;
use App\Fields\Sections\Hero;
use App\Fields\Sections\PageHeader;
use App\Fields\Sections\Spacer;
use App\Fields\Sections\ColleagueTestimonial;
use App\Fields\Sections\Features;
use App\Fields\Sections\Brands;
use App\Fields\Sections\Bcorp;
use App\Fields\Sections\Marquee;
use App\Fields\Sections\ImageCarousel;
use App\Fields\Sections\YoutubeEmbed;

/**
 * ACF field group for blog posts (post type "post").
 * Flexible sections let editors use the main post content and/or add custom sections (e.g. Text with WYSIWYG, CTA, Hero).
 */
class BlogFields
{
    public static function init(): void
    {
        acf_add_local_field_group([
            'key'                   => 'group_boozed_blog',
            'title'                 => __('Blog content', 'boozed'),
            'fields'                => self::get_fields(),
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'post',
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
                'key'           => 'field_boozed_blog_use_news_article_template',
                'label'         => __('Use fixed news layout', 'boozed'),
                'name'          => 'use_news_article_template',
                'type'          => 'true_false',
                'instructions'  => __('When enabled, the post uses the centered news article template (title, excerpt, share, featured image, main content only). Flexible sections below are hidden and not output.', 'boozed'),
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => __('Yes', 'boozed'),
                'ui_off_text'   => __('No', 'boozed'),
            ],
            [
                'key'               => 'field_boozed_blog_sections',
                'label'             => __('Sections', 'boozed'),
                'name'              => 'sections',
                'type'              => 'flexible_content',
                'button_label'      => __('Add section', 'boozed'),
                'instructions'      => __('Build the blog post with sections. Use "Post content" to output the main editor content at a chosen position, or add custom sections (e.g. Text with WYSIWYG, CTA, Hero). Leave empty to use the default: title, featured image, then main content.', 'boozed'),
                'conditional_logic' => [
                    [
                        [
                            'field'    => 'field_boozed_blog_use_news_article_template',
                            'operator' => '!=',
                            'value'    => '1',
                        ],
                    ],
                ],
                'layouts'           => boozed_filter_sections_by_visibility([
                    'layout_boozed_post_content' => PostContent::get(),
                    'layout_boozed_tekst'        => Tekst::get(),
                    'layout_boozed_tekst_media'  => TekstMedia::get(),
                    'layout_boozed_cta'          => Cta::get(),
                    'layout_boozed_hero'         => Hero::get(),
                    'layout_boozed_page_header'  => PageHeader::get(),
                    'layout_boozed_spacer'       => Spacer::get(),
                    'layout_boozed_colleague_testimonial' => ColleagueTestimonial::get(),
                    'layout_boozed_features'    => Features::get(),
                    'layout_boozed_brands'       => Brands::get(),
                    'layout_boozed_bcorp'       => Bcorp::get(),
                    'layout_boozed_marquee'     => Marquee::get(),
                    'layout_boozed_image_carousel' => ImageCarousel::get(),
                    'layout_boozed_youtube_embed'  => YoutubeEmbed::get(),
                ]),
            ],
        ];
    }
}
