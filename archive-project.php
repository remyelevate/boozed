<?php
/**
 * Project post type archive (/projecten).
 * Renders the projects lister section so the archive uses the same layout as
 * when the lister is used on a flexible page.
 */

get_header();

// Render the projects lister section (uses main query when on this archive).
$part = get_template_directory() . '/resources/views/sections/projects_lister.php';
if (is_file($part)) {
	include $part;
}

get_footer();
