<?php
/**
 * Thema post type archive (/themas).
 *
 * WordPress routes /themas/ here because the thema CPT has
 * has_archive => true with rewrite slug "themas".
 *
 * Instead of only rendering the lister, we look up the WP page with slug
 * "themas" and render its ACF sections (page_header, theme_lister, etc.)
 * so editors can build the archive page visually — just like any other page.
 *
 * Falls back to the bare theme_lister section when no page is found.
 */

get_header();

// Find the WP page with slug "themas" to pull its ACF sections.
$archive_page = get_page_by_path('themas');

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
	// Fallback: render the theme lister section directly.
	$part = get_template_directory() . '/resources/views/sections/theme_lister.php';
	if (is_file($part)) {
		include $part;
	}
}

get_footer();
