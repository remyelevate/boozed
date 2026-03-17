<?php
/**
 * Vacature post type archive (/vacatures).
 *
 * WordPress routes /vacatures/ here because the vacature CPT has
 * has_archive => true with rewrite slug "vacatures".
 *
 * Instead of only rendering the lister, we look up the WP page with slug
 * "vacatures" and render its ACF sections (page_header, vacature_lister, etc.)
 * so editors can build the archive page visually — just like any other page.
 *
 * Falls back to the bare vacature_lister section when no page is found.
 */

get_header();

// Find the WP page with slug "vacatures" to pull its ACF sections.
$archive_page = get_page_by_path('vacatures');

if ($archive_page && function_exists('have_rows') && have_rows('sections', $archive_page->ID)) {
	while (have_rows('sections', $archive_page->ID)) {
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
	// Fallback: render the vacature lister section directly.
	$part = get_template_directory() . '/resources/views/sections/vacature_lister.php';
	if (is_file($part)) {
		include $part;
	}
}

get_footer();
