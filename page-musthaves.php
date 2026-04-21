<?php
/**
 * Dedicated template for /musthaves page.
 */

get_header();

$view = get_template_directory() . '/resources/views/pages/musthaves.php';
if (is_file($view)) {
    include $view;
}

get_footer();
