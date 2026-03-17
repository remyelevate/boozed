<?php

/**
 * Flexible page template: build pages by adding/removing ACF sections.
 * Uses the "sections" flexible content field; falls back to default content when empty.
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
    }

    // Optional: show default editor content below sections or when no sections
    if (get_the_content()) {
        ?>
        <section class="page-content">
            <div class="page-content-inner">
                <?php the_content(); ?>
            </div>
        </section>
        <?php
    }
}

get_footer();
