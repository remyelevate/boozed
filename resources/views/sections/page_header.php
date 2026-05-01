<?php

/**
 * Page header section
 * Background (light/dark) and content type (content+CTAs vs two columns) are toggled separately.
 * E.g. dark background with content and CTAs is supported.
 * Height follows content (no fixed min-height on desktop). Nexa Bold 124px title with ticker effect.
 * Content is top-aligned so the title stays fixed when subtitle length changes.
 *
 * When $page_header_override is set (e.g. from single post template), title/background are taken from it
 * and ACF sub_fields are skipped; other keys can be omitted for title-only headers.
 * Optional override keys:
 * - title_top_spacing — 'min', '50', or '150' (see Page header ACF).
 * - show_post_meta (bool) + post_id (int) — single blog: date, author, reading time, breadcrumbs under title.
 */

$override = isset($page_header_override) && is_array($page_header_override) ? $page_header_override : [];

$page_header_show_post_meta = false;
$page_header_meta_post_id   = 0;

if ($override !== []) {
	$title      = isset($override['title']) ? (string) $override['title'] : '';
	$background = isset($override['background']) ? $override['background'] : 'light';
	$content_type = 'content_and_ctas';
	$content   = '';
	$primary_label   = '';
	$primary_url     = '';
	$secondary_label = '';
	$secondary_url   = '';
	$desc_left  = '';
	$desc_right = '';
	$title_top_spacing = isset($override['title_top_spacing']) ? (string) $override['title_top_spacing'] : 'min';
	if (!empty($override['show_post_meta'])) {
		$page_header_show_post_meta = true;
		$page_header_meta_post_id   = isset($override['post_id']) ? (int) $override['post_id'] : (int) get_the_ID();
	}
} else {
	$background   = function_exists('get_sub_field') ? get_sub_field('page_header_background') : null;
	$content_type = function_exists('get_sub_field') ? get_sub_field('page_header_content_type') : null;
	$variant      = function_exists('get_sub_field') ? get_sub_field('page_header_variant') : null; // legacy

	// Backward compatibility: if new fields not set, derive from old variant
	if ($background === null && $variant !== null) {
		$background = $variant;
	}
	if ($content_type === null && $variant !== null) {
		$content_type = ($variant === 'dark') ? 'columns' : 'content_and_ctas';
	}
	$background   = $background ?: 'light';
	$content_type = $content_type ?: 'content_and_ctas';

	$title = function_exists('get_sub_field') ? (string) get_sub_field('page_header_title') : '';
	// Content + CTAs fields
	$content         = function_exists('get_sub_field') ? get_sub_field('page_header_content') : '';
	$primary_label   = function_exists('get_sub_field') ? (string) get_sub_field('page_header_primary_label') : '';
	$primary_url     = function_exists('get_sub_field') ? (string) get_sub_field('page_header_primary_url') : '';
	$secondary_label = function_exists('get_sub_field') ? (string) get_sub_field('page_header_secondary_label') : '';
	$secondary_url   = function_exists('get_sub_field') ? (string) get_sub_field('page_header_secondary_url') : '';
	// Two columns fields
	$desc_left  = function_exists('get_sub_field') ? get_sub_field('page_header_description_left') : '';
	$desc_right = function_exists('get_sub_field') ? get_sub_field('page_header_description_right') : '';
	$title_top_spacing_raw = function_exists('get_sub_field') ? get_sub_field('page_header_title_top_spacing') : null;
	$title_top_spacing     = ($title_top_spacing_raw !== null && $title_top_spacing_raw !== false && $title_top_spacing_raw !== '')
		? (string) $title_top_spacing_raw
		: 'min';
}

if ($page_header_show_post_meta && $page_header_meta_post_id > 0) {
	$p = get_post($page_header_meta_post_id);
	if (!$p || $p->post_type !== 'post' || $p->post_status !== 'publish') {
		$page_header_show_post_meta = false;
		$page_header_meta_post_id   = 0;
	}
}

$page_header_nieuws_landing = ($override === [] && $title !== '' && function_exists('is_page') && is_page('nieuws'));

// Legacy ACF value "0" → minimum safe spacing
if ($title_top_spacing === '0') {
	$title_top_spacing = 'min';
}
$title_top_spacing_allowed = ['min', '50', '150'];
if (!in_array($title_top_spacing, $title_top_spacing_allowed, true)) {
	$title_top_spacing = 'min';
}
$page_header_pt_class = [
	'min' => 'page-header--title-top-min',
	'50'  => 'page-header--title-top-plus-50',
	'150' => 'page-header--title-top-plus-150',
][$title_top_spacing];

$is_dark  = ($background === 'dark');
$has_ctas = ($content_type === 'content_and_ctas');

$show_primary_btn   = $has_ctas && $primary_url !== '' && $primary_label !== '';
$show_secondary_btn = $has_ctas && $secondary_url !== '' && $secondary_label !== '';

// Vacature: "Direct solliciteren" (or #solliciteren URL) opens the sollicitatie modal
$page_header_primary_sollicitatie_modal = false;
$page_header_primary_btn_href           = $primary_url;
if (is_singular('vacature') && $show_primary_btn) {
	$pl = strtolower($primary_label);
	$pu = strtolower((string) $primary_url);
	if ((strpos($pl, 'direct') !== false && strpos($pl, 'solliciteren') !== false) || strpos($pu, '#solliciteren') !== false) {
		$page_header_primary_sollicitatie_modal = true;
		if (strpos($primary_url, '#solliciteren') === false) {
			$page_header_primary_btn_href = '#solliciteren';
		}
	}
}

$bg_class    = $is_dark ? 'bg-brand-indigo' : 'bg-brand-white';
$title_color = $is_dark ? 'text-brand-white' : 'text-brand-indigo';
$text_color  = $is_dark ? 'text-brand-white' : 'text-brand-black';

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';

$page_header_title_wrap_mb = ($page_header_show_post_meta || $page_header_nieuws_landing) ? 'mb-3 md:mb-4' : 'mb-4 md:mb-6';
?>
<section class="page-header <?php echo esc_attr($bg_class); ?> <?php echo esc_attr($page_header_pt_class); ?> min-h-0 flex flex-col justify-start overflow-x-hidden" data-page-header-background="<?php echo esc_attr($background); ?>" data-page-header-title-top-spacing="<?php echo esc_attr($title_top_spacing); ?>">
	<?php if ($title !== '') : ?>
		<div class="page-header__title-wrap w-full min-w-[100vw] overflow-x-hidden overflow-y-visible pointer-events-none pb-1 md:pb-2 <?php echo esc_attr($page_header_title_wrap_mb); ?>">
			<div class="page-header__title-inner flex whitespace-nowrap will-change-transform pl-4 md:pl-section-x" style="margin-left: max(0px, calc((100% - 1920px) / 2));">
				<h1 class="page-header__title font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] <?php echo esc_attr($title_color); ?> inline-block pr-[1em] pb-1 md:pb-2">
					<?php echo esc_html($title); ?>
				</h1>
				<span class="page-header__title-dupe font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] <?php echo esc_attr($title_color); ?> inline-block pr-[1em] pb-1 md:pb-2 hidden" aria-hidden="true">
					<?php echo esc_html($title); ?>
				</span>
			</div>
		</div>
		<?php if ($page_header_show_post_meta) : ?>
			<?php
			$post_id = $page_header_meta_post_id;
			include get_template_directory() . '/resources/views/partials/page-header-post-meta.php';
			?>
		<?php elseif ($page_header_nieuws_landing) : ?>
			<?php include get_template_directory() . '/resources/views/partials/page-header-nieuws-landing-breadcrumbs.php'; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($content_type === 'columns' && ($desc_left || $desc_right)) : ?>
		<div class="page-header__bottom max-w-section mx-auto w-full px-4 pb-10 md:px-section-x md:pb-section-y">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
				<div class="page-header__desc prose prose-lg font-body text-body-md <?php echo esc_attr($text_color); ?> max-w-none prose-invert md:pr-[25%]">
					<?php echo wp_kses_post(wpautop($desc_left)); ?>
				</div>
				<div class="page-header__desc prose prose-lg font-body text-body-md <?php echo esc_attr($text_color); ?> max-w-none prose-invert">
					<?php echo wp_kses_post(wpautop($desc_right)); ?>
				</div>
			</div>
		</div>
	<?php elseif ($content || $show_primary_btn || $show_secondary_btn) : ?>
		<div class="page-header__bottom max-w-section mx-auto w-full px-4 pb-10 md:px-section-x md:pb-section-y">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 md:items-end">
				<?php if ($content) : ?>
					<div class="page-header__body prose prose-lg font-body text-body-md <?php echo esc_attr($text_color); ?> max-w-none md:pr-[25%]">
						<?php echo wp_kses_post(wpautop($content)); ?>
					</div>
				<?php endif; ?>
				<?php if ($show_primary_btn || $show_secondary_btn) : ?>
					<div class="page-header__actions flex flex-wrap items-center gap-4 md:gap-6 <?php echo $content ? '' : 'md:col-start-2'; ?>">
						<?php if ($show_primary_btn) : ?>
							<?php
							\App\Components::render('button', [
								'variant'                    => 'coral',
								'label'                      => $primary_label,
								'href'                       => $page_header_primary_btn_href,
								'icon_right_html'            => $phosphor_chevron_right,
								'class'                      => '!bg-brand-coral',
								'vacature_sollicitatie_modal' => $page_header_primary_sollicitatie_modal,
							]);
							?>
						<?php endif; ?>
						<?php if ($show_secondary_btn) : ?>
							<a href="<?php echo esc_url($secondary_url); ?>" class="page-header__cta-secondary font-body text-body-md font-medium <?php echo $is_dark ? 'text-brand-white' : 'text-brand-indigo'; ?> no-underline relative inline-block hover:opacity-90 transition-opacity">
								<?php echo esc_html($secondary_label); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if ($title !== '' && empty($GLOBALS['boozed_page_header_ticker_script_printed'])) : $GLOBALS['boozed_page_header_ticker_script_printed'] = true; ?>
<script>
(function() {
	function runPageHeaderTicker() {
		document.querySelectorAll('.page-header__title-wrap').forEach(function(wrap) {
			var inner = wrap.querySelector('.page-header__title-inner');
			var dupe = wrap.querySelector('.page-header__title-dupe');
			if (!inner) return;
			// Temporarily show dupe to measure full ticker width
			if (dupe) dupe.classList.remove('hidden');
			var innerWidth = inner.scrollWidth;
			var viewportWidth = window.innerWidth;
			var shouldTick = innerWidth > viewportWidth;
			inner.classList.toggle('page-header__title-inner--ticker', shouldTick);
			if (dupe) dupe.classList.toggle('hidden', !shouldTick);
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			runPageHeaderTicker();
			if (document.fonts && document.fonts.ready) document.fonts.ready.then(runPageHeaderTicker);
			setTimeout(runPageHeaderTicker, 100);
		});
	} else {
		runPageHeaderTicker();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(runPageHeaderTicker);
		setTimeout(runPageHeaderTicker, 100);
	}
	window.addEventListener('resize', runPageHeaderTicker);
})();
</script>
<?php endif; ?>
