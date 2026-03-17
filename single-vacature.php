<?php
/**
 * Template for single Vacature (CPT).
 * Renders ACF flexible sections. Taxonomies (locatie, niveau, team, dienstverband) are available for filtering/display.
 */

get_header();

while (have_posts()) {
    the_post();

    $post_id = get_the_ID();
    if (function_exists('have_rows') && have_rows('sections', $post_id)) {
        while (have_rows('sections', $post_id)) {
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
        ?>
        <section class="page-content">
            <div class="page-content-inner">
                <h1><?php the_title(); ?></h1>
                <?php if (function_exists('get_field') && get_field('short_description')) : ?>
                    <p class="lead"><?php echo esc_html(get_field('short_description')); ?></p>
                <?php endif; ?>
                <?php the_content(); ?>
                <p><?php esc_html_e('Add sections in the Sections field above to build this vacature page.', 'boozed'); ?></p>
            </div>
        </section>
        <?php
    }
}

get_footer();
