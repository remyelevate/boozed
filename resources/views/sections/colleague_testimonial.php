<?php

/**
 * Colleague testimonial section
 * Pulls testimonials from the Testimonial CPT. Two columns: left = label, subtitle, title, dots (if multiple); right = image, full text, CTAs.
 * When multiple items: one slide visible at a time, dots to switch, optional autoplay.
 */

$label           = function_exists('get_sub_field') ? (string) get_sub_field('colleague_testimonial_label') : 'Boozed';
$subtitle        = function_exists('get_sub_field') ? (string) get_sub_field('colleague_testimonial_title') : '';
$count           = function_exists('get_sub_field') ? max(1, min(20, (int) get_sub_field('colleague_testimonial_count'))) : 4;
$autoplay        = function_exists('get_sub_field') ? (bool) get_sub_field('colleague_testimonial_autoplay') : true;
$primary_label   = function_exists('get_sub_field') ? (string) get_sub_field('colleague_testimonial_primary_label') : 'Contact';
$primary_url     = function_exists('get_sub_field') ? (string) get_sub_field('colleague_testimonial_primary_url') : '';
$secondary_label = function_exists('get_sub_field') ? (string) get_sub_field('colleague_testimonial_secondary_label') : 'Meer over onze werkwijze';
$secondary_url   = function_exists('get_sub_field') ? (string) get_sub_field('colleague_testimonial_secondary_url') : '';

$show_primary_btn   = $primary_url !== '' && $primary_label !== '';
$show_secondary_btn = $secondary_url !== '' && $secondary_label !== '';

$testimonials_query = new \WP_Query([
	'post_type'      => 'testimonial',
	'posts_per_page' => $count,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
]);

$items = [];
while ($testimonials_query->have_posts()) {
	$testimonials_query->the_post();
	$post_id   = get_the_ID();
	$title     = function_exists('get_field') ? (string) get_field('testimonial_title', $post_id) : '';
	$content   = function_exists('get_field') ? (string) get_field('testimonial_content', $post_id) : '';
	$full_text = $content !== '' ? $content : '';
	$thumb_id  = get_post_thumbnail_id($post_id);
	$items[]   = [
		'title'     => $title,
		'full_text' => $full_text,
		'image_url' => $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '',
	];
}
wp_reset_postdata();

$has_items   = ! empty($items);
$is_slider   = $has_items && count($items) > 1;
$section_id  = 'colleague-testimonial-' . (function_exists('get_row_index') ? get_row_index() : uniqid());

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>
<section class="colleague-testimonial max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y bg-white" id="<?php echo esc_attr($section_id); ?>" data-colleague-testimonial-autoplay="<?php echo $autoplay && $is_slider ? '1' : '0'; ?>">
	<div class="colleague-testimonial__grid grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-start">
		<div class="colleague-testimonial__heading order-2 md:order-1">
			<?php if ($label !== '' || $subtitle !== '') : ?>
				<div class="colleague-testimonial__header flex items-center gap-3 mb-6 md:mb-8">
					<?php if ($label !== '') : ?>
						<span class="colleague-testimonial__label font-body text-body-sm font-medium text-brand-black"><?php echo esc_html($label); ?></span>
					<?php endif; ?>
					<?php if ($subtitle !== '') : ?>
						<span class="colleague-testimonial__subtitle inline-flex items-center font-body text-body-sm text-brand-white bg-brand-black px-4 h-[40px]"><?php echo esc_html($subtitle); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($has_items) : ?>
				<div class="colleague-testimonial__slides-left relative">
					<?php foreach ($items as $i => $item) : ?>
						<div class="colleague-testimonial__slide colleague-testimonial__slide--left <?php echo $is_slider && $i !== 0 ? 'hidden' : ''; ?>" data-colleague-testimonial-slide="<?php echo (int) $i; ?>" role="tabpanel" id="<?php echo esc_attr($section_id); ?>-panel-<?php echo (int) $i; ?>" aria-labelledby="<?php echo esc_attr($section_id); ?>-dot-<?php echo (int) $i; ?>" <?php echo $is_slider && $i !== 0 ? 'aria-hidden="true"' : ''; ?>>
							<?php if ($item['title'] !== '') : ?>
								<h2 class="font-heading font-bold text-brand-black text-2xl md:text-3xl lg:text-[42px] lg:leading-[1.15]"><?php echo esc_html($item['title']); ?></h2>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ($is_slider) : ?>
					<nav class="colleague-testimonial__dots flex gap-2 mt-6" aria-label="<?php esc_attr_e('Testimonial navigation', 'boozed'); ?>" role="tablist">
						<?php foreach ($items as $i => $item) : ?>
							<button type="button" class="colleague-testimonial__dot w-3 h-3 rounded-sm transition-colors <?php echo $i === 0 ? 'bg-brand-coral' : 'bg-brand-indigo/30 hover:bg-brand-indigo/50'; ?>" role="tab" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($section_id); ?>-panel-<?php echo (int) $i; ?>" id="<?php echo esc_attr($section_id); ?>-dot-<?php echo (int) $i; ?>" data-colleague-testimonial-dot="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr(sprintf(__('Testimonial %d', 'boozed'), $i + 1)); ?>"></button>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<div class="colleague-testimonial__content order-1 md:order-2">
			<?php if ($has_items) : ?>
				<div class="colleague-testimonial__card">
					<div class="colleague-testimonial__slides-right">
						<?php foreach ($items as $i => $item) : ?>
							<div class="colleague-testimonial__slide colleague-testimonial__slide--right <?php echo $is_slider && $i !== 0 ? 'hidden' : ''; ?>" data-colleague-testimonial-slide="<?php echo (int) $i; ?>" role="tabpanel" aria-hidden="<?php echo $is_slider && $i !== 0 ? 'true' : 'false'; ?>">
								<?php if ($item['image_url'] !== '') : ?>
									<div class="colleague-testimonial__image-wrap flex flex-col items-center md:items-start mb-6">
										<img src="<?php echo esc_url($item['image_url']); ?>" alt="" class="colleague-testimonial__image w-[268px] h-[298px] object-cover" width="268" height="298" loading="lazy">
									</div>
								<?php endif; ?>
								<?php if ($item['full_text'] !== '') : ?>
									<div class="colleague-testimonial__quote font-body text-body-md text-brand-black mb-6">
										<?php echo $item['full_text']; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ($show_primary_btn || $show_secondary_btn) : ?>
						<div class="colleague-testimonial__actions flex flex-wrap items-center gap-4 md:gap-6">
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
								<a href="<?php echo esc_url($secondary_url); ?>" class="colleague-testimonial__cta-secondary inline-flex items-center gap-2 font-body text-body-md font-medium text-brand-black no-underline hover:opacity-90 transition-opacity">
									<?php echo esc_html($secondary_label); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ($is_slider && empty($GLOBALS['colleague_testimonial_slider_js'])) : $GLOBALS['colleague_testimonial_slider_js'] = true; ?>
<script>
(function() {
	function init() {
		var sections = document.querySelectorAll('[data-colleague-testimonial-autoplay]');
		sections.forEach(function(section) {
			var sectionId = section.id;
			if (!sectionId) return;
			var dots = section.querySelectorAll('[data-colleague-testimonial-dot]');
			var slidesLeft = section.querySelectorAll('.colleague-testimonial__slide--left[data-colleague-testimonial-slide]');
			var slidesRight = section.querySelectorAll('.colleague-testimonial__slide--right[data-colleague-testimonial-slide]');
			var autoplay = section.getAttribute('data-colleague-testimonial-autoplay') === '1';
			var count = slidesLeft.length;
			if (count <= 1) return;

			var activeIndex = 0;
			var autoplayInterval = null;
			var AUTOPLAY_MS = 5000;

			function setActive(index) {
				activeIndex = (index + count) % count;
				slidesLeft.forEach(function(s, i) {
					var hidden = i !== activeIndex;
					s.classList.toggle('hidden', hidden);
					s.setAttribute('aria-hidden', hidden ? 'true' : 'false');
				});
				slidesRight.forEach(function(s, i) {
					var hidden = i !== activeIndex;
					s.classList.toggle('hidden', hidden);
					s.setAttribute('aria-hidden', hidden ? 'true' : 'false');
				});
				dots.forEach(function(dot, i) {
					dot.setAttribute('aria-selected', i === activeIndex ? 'true' : 'false');
					dot.classList.toggle('bg-brand-coral', i === activeIndex);
					dot.classList.toggle('bg-brand-indigo/30', i !== activeIndex);
				});
			}

			function startAutoplay() {
				if (!autoplay) return;
				autoplayInterval = setInterval(function() {
					setActive(activeIndex + 1);
				}, AUTOPLAY_MS);
			}

			function stopAutoplay() {
				if (autoplayInterval) {
					clearInterval(autoplayInterval);
					autoplayInterval = null;
				}
			}

			dots.forEach(function(dot, i) {
				dot.addEventListener('click', function() {
					setActive(i);
					stopAutoplay();
					setTimeout(startAutoplay, 3000);
				});
			});

			startAutoplay();
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
<?php endif; ?>
