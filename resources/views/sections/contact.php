<?php

/**
 * Contact section
 * Background image, Lottie arrow pointing to spot card (icon + label + optional URL),
 * Contact Form 7 form with heading, bottom ticker from repeater items.
 */

$bg_image_id    = function_exists('get_sub_field') ? get_sub_field('contact_background_image') : null;
$heading        = function_exists('get_sub_field') ? (string) get_sub_field('contact_heading') : '';
$form_shortcode = function_exists('get_sub_field') ? (string) get_sub_field('contact_form_shortcode') : '';
$spot_icon_id   = function_exists('get_sub_field') ? get_sub_field('contact_spot_icon') : null;
$spot_label     = function_exists('get_sub_field') ? (string) get_sub_field('contact_spot_label') : '';
$spot_url       = function_exists('get_sub_field') ? (string) get_sub_field('contact_spot_url') : '';
$ticker_rows    = function_exists('get_sub_field') ? (array) get_sub_field('contact_ticker_items') : [];

$heading        = $heading ?: 'Hoe kunnen we je helpen?';
$bg_image_url   = $bg_image_id ? wp_get_attachment_image_url($bg_image_id, 'full') : '';
$theme_uri      = get_template_directory_uri();
$arrow_lottie   = $theme_uri . '/assets/animations/arrow.json';

$ticker_items = [];
foreach ($ticker_rows as $row) {
	$text = isset($row['contact_ticker_item_text']) ? trim((string) $row['contact_ticker_item_text']) : '';
	if ($text === '') {
		continue;
	}
	$ticker_items[] = [
		'text' => $text,
		'url'  => isset($row['contact_ticker_item_url']) ? trim((string) $row['contact_ticker_item_url']) : '',
	];
}
$has_ticker = count($ticker_items) > 0;
$has_spot   = $spot_label !== '' || $spot_icon_id;
?>
<section class="section-contact relative min-h-[400px] overflow-hidden <?php echo $bg_image_url ? '' : 'bg-brand-indigo'; ?>">
	<?php if ($bg_image_url) : ?>
		<div class="section-contact__bg absolute inset-0 z-0" aria-hidden="true">
			<div class="absolute inset-0 bg-cover bg-center" style="background-image: url(<?php echo esc_url($bg_image_url); ?>);"></div>
			<div class="absolute inset-0 bg-brand-black/40 backdrop-blur-[2px]" aria-hidden="true"></div>
		</div>
	<?php endif; ?>

	<div class="section-contact__inner relative z-10 max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
		<div class="section-contact__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
			<div class="section-contact__left relative flex flex-col justify-center order-2 lg:order-1 min-h-[300px] lg:min-h-[400px]">
				<?php if ($has_spot) : ?>
					<div class="section-contact__arrow-content relative w-full max-w-[340px] aspect-[1.05] -rotate-15 origin-bottom-left">
						<div class="section-contact__lottie absolute right-0 top-0 w-[82%] h-[82%] pointer-events-none z-20" aria-hidden="true" data-contact-arrow-lottie="<?php echo esc_url($arrow_lottie); ?>"></div>
						<div class="section-contact__spot absolute bottom-0 left-0 bg-brand-white rounded-lg shadow-md px-4 py-3 inline-flex items-center gap-3 z-10">
						<?php if ($spot_icon_id) : ?>
							<span class="section-contact__spot-icon shrink-0 w-[50px] h-[50px] flex items-center justify-center">
								<?php echo wp_get_attachment_image($spot_icon_id, 'thumbnail', false, ['class' => 'w-[50px] h-[50px] object-contain']); ?>
							</span>
						<?php endif; ?>
						<span class="section-contact__spot-label font-body text-body-md text-brand-indigo italic whitespace-nowrap">
							<?php if ($spot_url !== '') : ?>
								<a href="<?php echo esc_url($spot_url); ?>" class="text-brand-indigo no-underline hover:underline">&ldquo;<?php echo esc_html($spot_label); ?>&rdquo;</a>
							<?php else : ?>
								&ldquo;<?php echo esc_html($spot_label); ?>&rdquo;
							<?php endif; ?>
						</span>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="section-contact__right order-1 lg:order-2 flex flex-col justify-end min-h-[300px] lg:min-h-[400px]">
				<div class="section-contact__form-card bg-brand-white shadow-lg p-6 md:p-10 w-full">
					<?php if ($heading !== '') : ?>
						<h2 class="section-contact__heading font-heading font-bold text-h4 md:text-h3 text-brand-indigo mt-0 mb-6"><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>
					<?php if ($form_shortcode !== '') : ?>
						<div class="section-contact__form">
							<?php echo do_shortcode($form_shortcode); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if ($has_ticker) : ?>
		<div class="section-contact__ticker py-4 overflow-hidden">
			<div class="section-contact__ticker-inner section-contact__ticker-inner--animate flex whitespace-nowrap will-change-transform">
				<?php foreach (range(1, 6) as $copy) : ?>
					<div class="section-contact__ticker-strip flex items-center gap-6 shrink-0 pr-6" aria-hidden="<?php echo $copy > 1 ? 'true' : 'false'; ?>">
						<?php foreach ($ticker_items as $i => $item) : ?>
							<?php if ($i > 0) : ?>
								<span class="section-contact__ticker-dot w-1.5 h-1.5 rounded-full bg-brand-white/80 shrink-0" aria-hidden="true"></span>
							<?php endif; ?>
							<span class="section-contact__ticker-item font-body text-body-sm text-brand-white">
								<?php if ($item['url'] !== '') : ?>
									<a href="<?php echo esc_url($item['url']); ?>" class="text-brand-white no-underline hover:underline"><?php echo esc_html($item['text']); ?></a>
								<?php else : ?>
									<?php echo esc_html($item['text']); ?>
								<?php endif; ?>
							</span>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if ($has_spot && empty($GLOBALS['boozed_contact_arrow_script_printed'])) : ?>
	<?php $GLOBALS['boozed_contact_arrow_script_printed'] = true; ?>
	<script>
	(function() {
		var DELAY_MS = 400;
		function init() {
			var section = document.querySelector('.section-contact');
			var arrowContainer = section ? section.querySelector('.section-contact__lottie') : null;
			var lottieUrl = arrowContainer ? arrowContainer.getAttribute('data-contact-arrow-lottie') : '';
			if (!section || !arrowContainer || !lottieUrl || typeof lottie === 'undefined') return;
			var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			if (reducedMotion) return;
			var anim = null;
			var playTimeout = null;
			function playOneshot() {
				try {
					if (!anim) {
						anim = lottie.loadAnimation({
							container: arrowContainer,
							renderer: 'svg',
							loop: false,
							autoplay: false,
							path: lottieUrl
						});
						anim.addEventListener('complete', function() { anim.goToAndStop(anim.totalFrames - 1, true); });
					}
					anim.goToAndPlay(0, true);
				} catch (e) {}
			}
			var observer = new IntersectionObserver(
				function(entries) {
					entries.forEach(function(entry) {
						if (!entry.isIntersecting) return;
						if (playTimeout) clearTimeout(playTimeout);
						playTimeout = setTimeout(playOneshot, DELAY_MS);
					});
				},
				{ threshold: 0.2, rootMargin: '0px 0px 50px 0px' }
			);
			observer.observe(section);
		}
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
		else init();
	})();
	</script>
<?php endif; ?>
