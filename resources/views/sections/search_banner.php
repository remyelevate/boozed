<?php

/**
 * Search banner section
 * Dark background; left: title + search input (submit on Enter → search results page); right: title + body text.
 */

$search_title   = function_exists('get_sub_field') ? (string) get_sub_field('search_banner_search_title') : '';
$placeholder    = function_exists('get_sub_field') ? (string) get_sub_field('search_banner_placeholder') : '';
$results_url    = function_exists('get_sub_field') ? (string) get_sub_field('search_banner_results_url') : '';
$right_title    = function_exists('get_sub_field') ? (string) get_sub_field('search_banner_right_title') : '';
$right_body     = function_exists('get_sub_field') ? (string) get_sub_field('search_banner_right_body') : '';

$search_title   = $search_title ?: 'Waar ben je naar op zoek?';
$placeholder    = $placeholder ?: 'Zoek naar meer dan 2000 items uit onze catalogus';
$right_title    = $right_title ?: 'Wat kun je huren?';
$right_body     = $right_body ?: "Om je een indruk te geven van wat wij allemaal verhuren hebben wij onze productcategoriën hieronder voor je uitgewerkt. Weet je al wat je nodig hebt? Gebruik dan de zoekfunctie hiernaast. Wil je liever gelijk alle verhuurproducten bekijken? Klik dan hiernaast.";

// Search results page: use ACF URL or default to the assortment (PLP) page.
$assortiment_url = function_exists('boozed_plp_url') ? boozed_plp_url() : home_url('/assortiment/');
$form_action = $results_url !== '' ? $results_url : $assortiment_url;

// Some editors/configurations may still point to the /zoeken/ page (which is currently broken).
// If configured to /zoeken/, always fall back to assortiment to avoid a 404.
$form_action_path = trim((string) parse_url((string) $form_action, PHP_URL_PATH), '/');
if ($form_action_path === 'zoeken') {
	$form_action = $assortiment_url;
}

$search_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1.25em" height="1.25em" viewBox="0 0 256 256" fill="currentColor" class="text-brand-black/50 shrink-0" aria-hidden="true"><path d="m228.24 219.76l-51.38-51.38a86.15 86.15 0 1 0-8.48 8.48l51.38 51.38a6 6 0 0 0 8.48-8.48ZM38 118a76 76 0 1 1 76 76a76.08 76.08 0 0 1-76-76Z"/></svg>';
?>
<section class="search-banner bg-brand-indigo text-brand-white py-12 md:py-16">
	<div class="search-banner__inner max-w-section mx-auto px-4 md:px-section-x w-full">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
			<div class="search-banner__left">
				<h4 class="search-banner__title font-heading font-bold text-h4 md:text-h4-lg text-brand-white mb-6"><?php echo esc_html($search_title); ?></h4>
				<form class="search-banner__form" action="<?php echo esc_url($form_action); ?>" method="get" role="search">
					<label for="search-banner-input" class="sr-only"><?php esc_attr_e('Search', 'boozed'); ?></label>
					<div class="search-banner__input-wrap flex items-center gap-3 bg-brand-white px-4 md:px-5 py-3 md:py-4">
						<span class="search-banner__icon" aria-hidden="true"><?php echo $search_icon_svg; ?></span>
						<input
							type="search"
							id="search-banner-input"
							name="s"
							class="search-banner__input flex-1 min-w-0 bg-transparent border-0 font-body text-body-md text-brand-black placeholder:text-brand-black/50 focus:outline-none focus:ring-0"
							placeholder="<?php echo esc_attr($placeholder); ?>"
							autocomplete="off"
							value="<?php echo esc_attr(get_search_query()); ?>"
						>
					</div>
					<button type="submit" class="sr-only"><?php esc_html_e('Search', 'boozed'); ?></button>
				</form>
			</div>
			<div class="search-banner__right">
				<h4 class="search-banner__right-title font-heading font-bold text-h4 md:text-h4-lg text-brand-white mb-4 md:mb-6"><?php echo esc_html($right_title); ?></h4>
				<div class="search-banner__body font-body text-body-md text-brand-white whitespace-pre-line"><?php echo esc_html($right_body); ?></div>
			</div>
		</div>
	</div>
</section>
