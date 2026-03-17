<?php
/**
 * Template Name: Flexible page
 * Template Post Type: page
 *
 * Page template that only renders ACF sections (no default block/content area).
 * Use this when you want to build the entire page from sections only.
 */

get_header();

while (have_posts()) {
    the_post();

    $page_id = get_the_ID();
    if (function_exists('have_rows') && have_rows('sections', $page_id)) {
        while (have_rows('sections', $page_id)) {
            the_row();
            $layout = get_row_layout();
            if (function_exists('boozed_section_enabled') && !boozed_section_enabled($layout)) {
                continue;
            }
            $name   = function_exists('boozed_section_layout_name') ? boozed_section_layout_name($layout) : $layout;
            $part   = get_template_directory() . '/resources/views/sections/' . $name . '.php';
            if ($name && is_file($part)) {
                include $part;
            }
        }
    } else {
        // No sections: show placeholder or title only
        ?>
        <section class="page-content">
            <div class="page-content-inner">
                <h1><?php the_title(); ?></h1>
                <p><?php esc_html_e('Add sections in the Page sections field above to build this page.', 'boozed'); ?></p>
            </div>
        </section>
        <?php
    }
}

get_footer();
