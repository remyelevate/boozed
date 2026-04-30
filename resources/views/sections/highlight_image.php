<?php

/**
 * Highlight image section
 * Container-wide/high image with Lottie arrow and content div (icon + label + optional URL).
 * Same image proportions as project_featured_image (banner). Arrow & content: left or right.
 */

$bg_image_id   = function_exists('get_sub_field') ? get_sub_field('highlight_image_background') : null;
$position      = function_exists('get_sub_field') ? (string) get_sub_field('highlight_image_position') : 'right';
$content_icon  = function_exists('get_sub_field') ? get_sub_field('highlight_image_icon') : null;
$content_label = function_exists('get_sub_field') ? (string) get_sub_field('highlight_image_label') : '';
$content_url   = function_exists('get_sub_field') ? (string) get_sub_field('highlight_image_url') : '';

$position     = $position === 'left' ? 'left' : 'right';
$bg_image_url = $bg_image_id ? wp_get_attachment_image_url($bg_image_id, 'full') : '';
$theme_uri    = get_template_directory_uri();
$arrow_lottie = $theme_uri . '/assets/animations/arrow.json';

$has_content = $content_label !== '' || $content_icon;
$is_right    = $position === 'right';

if (!$bg_image_url) {
    return;
}
?>
<section class="section-highlight-image w-full" aria-hidden="true">
	<div class="section-highlight-image__inner max-w-section mx-auto w-full">
		<div class="section-highlight-image__image-wrap relative w-full h-[50vw] min-h-[280px] md:h-[800px] overflow-hidden bg-brand-border">
			<div class="absolute inset-0 z-0">
				<img src="<?php echo esc_url($bg_image_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
				<div class="absolute inset-0 bg-brand-black/40 backdrop-blur-[2px]" aria-hidden="true"></div>
			</div>

			<div class="section-highlight-image__overlay absolute inset-0 z-10 flex px-4 md:px-section-x py-8 md:py-12">
				<div class="section-highlight-image__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center w-full max-w-section mx-auto">
					<?php if ($is_right) : ?>
						<div class="section-highlight-image__col-empty" aria-hidden="true"></div>
					<?php endif; ?>

					<div class="section-highlight-image__col-content relative flex flex-col justify-end min-h-[300px] lg:min-h-[400px]">
						<?php if ($has_content) : ?>
							<div class="section-highlight-image__arrow-content relative w-full max-w-[340px] aspect-[1.05] -rotate-15 origin-bottom-left">
								<div class="section-highlight-image__lottie absolute right-0 top-0 w-[82%] h-[82%] pointer-events-none z-20" aria-hidden="true" data-highlight-arrow-lottie="<?php echo esc_url($arrow_lottie); ?>"></div>
								<div class="section-highlight-image__spot absolute bottom-0 left-0 bg-brand-white rounded-lg shadow-md px-4 py-3 inline-flex items-center gap-3 z-10">
									<?php if ($content_icon) : ?>
										<span class="section-highlight-image__spot-icon shrink-0 w-[50px] h-[50px] flex items-center justify-center">
											<?php echo wp_get_attachment_image($content_icon, 'thumbnail', false, ['class' => 'w-[50px] h-[50px] object-contain']); ?>
										</span>
									<?php endif; ?>
									<span class="section-highlight-image__spot-label font-body text-body-md text-brand-indigo italic whitespace-nowrap">
										<?php if ($content_url !== '') : ?>
											<a href="<?php echo esc_url($content_url); ?>" class="text-brand-indigo no-underline hover:underline"><?php echo esc_html($content_label); ?></a>
										<?php else : ?>
											<?php echo esc_html($content_label); ?>
										<?php endif; ?>
									</span>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<?php if (! $is_right) : ?>
						<div class="section-highlight-image__col-empty" aria-hidden="true"></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if ($has_content && empty($GLOBALS['boozed_highlight_image_arrow_script_printed'])) : ?>
	<?php $GLOBALS['boozed_highlight_image_arrow_script_printed'] = true; ?>
	<script>
	(function() {
		var DELAY_MS = 400;
		function init() {
			var sections = document.querySelectorAll('.section-highlight-image');
			if (!sections.length || typeof lottie === 'undefined') return;
			var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			if (reducedMotion) return;
			sections.forEach(function(section) {
				var arrowContainer = section.querySelector('.section-highlight-image__lottie');
				var lottieUrl = arrowContainer ? arrowContainer.getAttribute('data-highlight-arrow-lottie') : '';
				if (!arrowContainer || !lottieUrl) return;
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
			});
		}
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
		else init();
	})();
	</script>
<?php endif; ?>
