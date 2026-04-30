<?php
/**
 * Template for single blog post.
 * Renders: page header (marquee title + date, author, reading time, breadcrumbs), optional featured image banner.
 * If the post has ACF flexible sections, those are rendered; otherwise the main post content is shown.
 */

get_header();

while (have_posts()) {
    the_post();

    $post_id   = get_the_ID();
    $thumb_id  = get_post_thumbnail_id($post_id);
    $has_thumb = (int) $thumb_id > 0;
    $has_sections = function_exists('have_rows') && have_rows('sections', $post_id);

    $page_header_override = [
        'title'             => get_the_title(),
        'background'        => 'light',
        'title_top_spacing' => 'min',
        'show_post_meta'    => true,
        'post_id'           => $post_id,
    ];
    include get_template_directory() . '/resources/views/sections/page_header.php';
    unset($page_header_override);
    ?>

    <?php if ($has_thumb) : ?>
        <!-- Banner: featured image -->
        <section class="single-post-banner w-full bg-brand-border" aria-hidden="true">
            <div class="single-post-banner__inner max-w-section mx-auto w-full">
                <div class="single-post-banner__image-wrap w-full h-[50vw] min-h-[280px] md:h-[400px] overflow-hidden">
                    <?php echo get_the_post_thumbnail($post_id, 'full', ['class' => 'w-full h-full object-cover', 'loading' => 'eager']); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($has_sections) : ?>
        <!-- Flexible ACF sections (Post content, Text/WYSIWYG, CTA, Hero, etc.) -->
        <?php
        while (have_rows('sections', $post_id)) {
            the_row();
            $layout = get_row_layout();
            if (function_exists('boozed_section_enabled') && !boozed_section_enabled($layout)) {
                continue;
            }
            $name = function_exists('boozed_section_layout_name') ? boozed_section_layout_name($layout) : $layout;
            $part = get_template_directory() . '/resources/views/sections/' . $name . '.php';
            if ($name && is_file($part)) {
                include $part;
            }
        }
        ?>
    <?php else : ?>
        <!-- Fallback: post content in container with prose styling -->
        <section class="single-post-content page-content bg-brand-white">
            <div class="single-post-content__inner max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y">
                <div class="entry-content prose prose-lg font-body text-body-md text-brand-black max-w-none">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php
}

get_footer();
