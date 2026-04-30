<?php

/**
 * BCorp section
 * Dark background, container-wide Lottie confetti (one-shot on view), image left, title + content + buttons right. ACF: image, title, content, primary/secondary buttons.
 */

$image_id            = function_exists('get_sub_field') ? get_sub_field('bcorp_image') : null;
$white_background    = function_exists('get_sub_field') ? (bool) get_sub_field('bcorp_white_background') : false;
$title               = function_exists('get_sub_field') ? (string) get_sub_field('bcorp_title') : '';
$content             = function_exists('get_sub_field') ? get_sub_field('bcorp_content') : '';
$primary_label       = function_exists('get_sub_field') ? (string) get_sub_field('bcorp_primary_label') : '';
$primary_url         = function_exists('get_sub_field') ? (string) get_sub_field('bcorp_primary_url') : '';
$secondary_label     = function_exists('get_sub_field') ? (string) get_sub_field('bcorp_secondary_label') : '';
$secondary_url       = function_exists('get_sub_field') ? (string) get_sub_field('bcorp_secondary_url') : '';

$image_url         = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
$show_primary_btn  = $primary_url !== '' && $primary_label !== '';
$show_secondary_btn = $secondary_url !== '' && $secondary_label !== '';
$confetti_url      = get_template_directory_uri() . '/assets/animations/confetti.json';

$section_bg_class = $white_background ? 'bg-brand-white' : 'bg-brand-indigo';
$text_class       = $white_background ? 'text-brand-black' : 'text-brand-white';
$image_class      = $white_background ? 'invert' : '';

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>
<section class="bcorp relative overflow-hidden <?php echo esc_attr($section_bg_class); ?> min-h-[400px] min-h-[100dvh] md:min-h-0 md:h-[1000px] flex flex-col justify-center" data-bcorp-confetti="<?php echo esc_url($confetti_url); ?>"<?php echo $title !== '' ? ' aria-label="' . esc_attr($title) . '"' : ''; ?>>
	<div class="bcorp__lottie absolute inset-0 z-0 w-full h-full min-h-full pointer-events-none" aria-hidden="true"></div>

	<div class="bcorp__content relative z-10 max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y w-full flex flex-col justify-center">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-center">
			<?php if ($image_url) : ?>
				<div class="bcorp__media order-2 md:order-1 flex items-center justify-center">
					<img src="<?php echo esc_url($image_url); ?>" alt="" class="bcorp__image max-w-full h-auto object-contain <?php echo esc_attr($image_class); ?>" loading="lazy">
				</div>
			<?php endif; ?>

			<div class="bcorp__text order-1 md:order-2 <?php echo $image_url ? '' : 'md:col-span-2'; ?>">
				<?php if ($title !== '') : ?>
					<h2 class="font-heading font-bold text-h2 md:text-h2-lg <?php echo esc_attr($text_class); ?> mb-4 md:mb-6"><?php echo esc_html($title); ?></h2>
				<?php endif; ?>
				<?php if ($content) : ?>
					<div class="bcorp__body prose prose-lg font-body text-body-md <?php echo esc_attr($text_class); ?> max-w-none mb-6">
						<?php echo wp_kses_post(wpautop($content)); ?>
					</div>
				<?php endif; ?>
				<?php if ($show_primary_btn || $show_secondary_btn) : ?>
					<div class="bcorp__actions flex flex-wrap items-center gap-4 md:gap-6">
						<?php if ($show_primary_btn) : ?>
							<?php
							\App\Components::render('button', [
								'variant'          => 'coral',
								'label'            => $primary_label,
								'href'             => $primary_url,
								'icon_right_html'  => $phosphor_chevron_right,
								'class'            => '!bg-brand-coral',
							]);
							?>
						<?php endif; ?>
						<?php if ($show_secondary_btn) : ?>
							<a href="<?php echo esc_url($secondary_url); ?>" class="bcorp__cta-secondary font-body text-body-md font-medium <?php echo esc_attr($text_class); ?> no-underline relative inline-block hover:opacity-90 transition-opacity">
								<?php echo esc_html($secondary_label); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_bcorp_script_printed'])) : $GLOBALS['boozed_bcorp_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		var section = document.querySelector('.bcorp');
		var container = section ? section.querySelector('.bcorp__lottie') : null;
		var confettiUrl = section ? section.getAttribute('data-bcorp-confetti') : '';
		if (!section || !container || !confettiUrl || typeof lottie === 'undefined') return;

		var played = false;
		var observer = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (played) return;
				if (!entry.isIntersecting) return;
				played = true;
				if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
				try {
					var anim = lottie.loadAnimation({
						container: container,
						renderer: 'svg',
						loop: false,
						autoplay: true,
						path: confettiUrl
					});
					anim.addEventListener('complete', function() {
						anim.destroy();
					});
				} catch (e) {}
				observer.disconnect();
			});
		}, { threshold: 0.1, rootMargin: '0px 0px 50px 0px' });

		observer.observe(section);
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
<?php endif; ?>
