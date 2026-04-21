<?php
/**
 * Private wishlist index route template.
 * URL: /wishlist/
 */

get_header();

$view = get_template_directory() . '/resources/views/pages/musthaves.php';
if (is_file($view)) {
    include $view;
}

get_footer();
