<?php

/**
 * Thank You section
 * Indigo background, Lottie confetti (one-shot on view), title (Nexa Bold 124px), subtitle (h3), description (body), coral CTA button.
 */

$title        = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_title') : '';
$subtitle     = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_subtitle') : '';
$description  = function_exists('get_sub_field') ? get_sub_field('thank_you_description') : '';
$button_text  = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_button_text') : '';
$button_url   = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_button_url') : '';

$show_button = $button_url !== '' && $button_text !== '';
$confetti_url = get_template_directory_uri() . '/assets/animations/confetti.json';

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>
<section class="thank-you relative overflow-hidden bg-brand-indigo min-h-screen min-h-[100dvh] flex flex-col justify-center" data-thank-you-confetti="<?php echo esc_url($confetti_url); ?>"<?php echo $title !== '' ? ' aria-label="' . esc_attr($title) . '"' : ''; ?>>
	<div class="thank-you__lottie absolute inset-0 z-0 w-full h-full min-h-full pointer-events-none" aria-hidden="true"></div>

	<div class="thank-you__content relative z-10 max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y w-full flex flex-col justify-center">
		<div class="thank-you__inner md:max-w-[50%]">
			<?php if ($title !== '') : ?>
				<h2 class="thank-you__title font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] text-brand-white mb-4 md:mb-6"><?php echo esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($subtitle !== '') : ?>
				<h3 class="thank-you__subtitle font-heading font-bold text-h3 md:text-h3-lg text-brand-white mb-4"><?php echo esc_html($subtitle); ?></h3>
			<?php endif; ?>
			<?php if ($description) : ?>
				<div class="thank-you__description font-body text-body-md text-brand-white prose prose-invert max-w-none mb-6">
					<?php echo wp_kses_post(wpautop($description)); ?>
				</div>
			<?php endif; ?>
			<?php if ($show_button) : ?>
				<?php
				\App\Components::render('button', [
					'variant'         => 'coral',
					'label'           => $button_text,
					'href'            => $button_url,
					'icon_right_html' => $phosphor_chevron_right,
					'class'           => '!bg-brand-coral',
				]);
				?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_thank_you_script_printed'])) : $GLOBALS['boozed_thank_you_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		var section = document.querySelector('.thank-you');
		var container = section ? section.querySelector('.thank-you__lottie') : null;
		var confettiUrl = section ? section.getAttribute('data-thank-you-confetti') : '';
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
