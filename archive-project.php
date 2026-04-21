<?php
/**
 * Project post type archive (/projecten).
 * Renders the projects lister section so the archive uses the same layout as
 * when the lister is used on a flexible page.
 */

get_header();

$has_custom_sections = false;

if (function_exists('have_rows') && have_rows('project_archive_sections', 'option')) {
	$has_custom_sections = true;
	while (have_rows('project_archive_sections', 'option')) {
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
}

if (! $has_custom_sections) {
	// Fallback: keep existing archive behaviour if no ACF sections are configured yet.
	$part = get_template_directory() . '/resources/views/sections/projects_lister.php';
	if (is_file($part)) {
		include $part;
	}
}

get_footer();
