<?php

/**
 * Map banner section
 * Full-width OpenStreetMap (Leaflet) map. Default: Schieweg 64, Delft, Netherlands.
 */

$title   = function_exists('get_sub_field') ? (string) get_sub_field('map_banner_title') : '';
$address = function_exists('get_sub_field') ? (string) get_sub_field('map_banner_address') : '';
$zoom    = function_exists('get_sub_field') ? (int) get_sub_field('map_banner_zoom') : 16;
$height  = function_exists('get_sub_field') ? (int) get_sub_field('map_banner_height') : 400;

if ($address === '') {
	$address = 'Schieweg 64, 2627 AN Delft, Netherlands';
}
$zoom = max(1, min(19, $zoom));
$height = max(200, min(800, $height));

// Default center: Schieweg 64, Delft
$default_lat = 51.9966;
$default_lng = 4.3644;

$map_id = 'map-banner-' . uniqid();

// Enqueue Leaflet (OpenStreetMap) only when this section is used
$theme_uri = get_template_directory_uri();
$theme_dir = get_template_directory();
wp_enqueue_style(
	'leaflet',
	'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
	[],
	'1.9.4'
);
wp_enqueue_script(
	'leaflet',
	'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
	[],
	'1.9.4',
	true
);
$map_banner_js = $theme_dir . '/assets/js/map-banner.js';
if (file_exists($map_banner_js)) {
	wp_enqueue_script(
		'boozed-map-banner',
		$theme_uri . '/assets/js/map-banner.js',
		['leaflet'],
		filemtime($map_banner_js),
		true
	);
}
?>
<section class="map-banner bg-brand-white overflow-hidden" aria-label="<?php esc_attr_e('Location map', 'boozed'); ?>">
	<div class="map-banner__inner max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y">
		<?php if ($title !== '') : ?>
			<div class="map-banner__header mb-6 md:mb-8">
				<h2 class="map-banner__title font-heading font-bold text-h4 md:text-h4-lg text-brand-black"><?php echo esc_html($title); ?></h2>
			</div>
		<?php endif; ?>
		<div
			id="<?php echo esc_attr($map_id); ?>"
			class="map-banner__map w-full bg-brand-border rounded overflow-hidden"
			style="height: <?php echo (int) $height; ?>px;"
			data-address="<?php echo esc_attr($address); ?>"
			data-zoom="<?php echo (int) $zoom; ?>"
			data-default-lat="<?php echo esc_attr($default_lat); ?>"
			data-default-lng="<?php echo esc_attr($default_lng); ?>"
		></div>
	</div>
</section>
