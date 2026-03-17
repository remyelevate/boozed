<?php

/**
 * Instagram slider section.
 * First card: CTA "Volg ons op Instagram" (dark). Remaining cards: manual post images with optional caption overlay.
 * Uses design system: max-w-section, px-section-x, py-section-y, font-heading, font-body, brand-*.
 */

$cta_heading = function_exists('get_sub_field') ? (string) get_sub_field('instagram_slider_cta_heading') : '';
$cta_body    = function_exists('get_sub_field') ? (string) get_sub_field('instagram_slider_cta_body') : '';
$cta_url     = function_exists('get_sub_field') ? (string) get_sub_field('instagram_slider_cta_url') : '';
$posts       = function_exists('get_sub_field') ? (array) get_sub_field('instagram_slider_posts') : [];

$cta_heading = $cta_heading ?: __('Volg ons op Instagram', 'boozed');
$cta_body    = $cta_body ?: __('Wij zijn creatieve architecten die jouw verhaal met impact tot leven brengen. Merken zichtbaar en tastbaar maken.', 'boozed');

$post_items = [];
if (is_array($posts)) {
	foreach ($posts as $row) {
		$img_id = isset($row['image']) ? (int) $row['image'] : 0;
		if (!$img_id) continue;
		$img_url = wp_get_attachment_image_url($img_id, 'large');
		if (!$img_url) continue;
		$post_items[] = [
			'image_url' => $img_url,
			'caption'   => isset($row['caption']) ? (string) $row['caption'] : '',
			'link'      => isset($row['link']) ? esc_url($row['link']) : '',
		];
	}
}

$section_id = 'instagram-slider-' . (function_exists('get_row_index') ? get_row_index() : uniqid());

// Instagram icon (outline)
$instagram_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>';
?>

<section class="instagram-slider max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y" id="<?php echo esc_attr($section_id); ?>">
	<div class="instagram-slider__wrap relative">
		<div class="instagram-slider__track flex gap-4 md:gap-6 overflow-x-auto overflow-y-hidden scroll-smooth scrollbar-width-none -mx-4 px-4 md:mx-0 md:px-0" data-instagram-slider-track aria-label="<?php esc_attr_e('Instagram feed', 'boozed'); ?>">
			<?php /* CTA card (first) */ ?>
			<div class="instagram-slider__card instagram-slider__card--cta shrink-0 w-[280px] md:w-[320px] h-[280px] md:h-[320px] flex flex-col justify-between bg-brand-indigo p-6 md:p-8 text-brand-white">
				<div>
					<div class="instagram-slider__icon text-brand-white mb-4"><?php echo $instagram_icon; ?></div>
					<h3 class="font-heading font-bold text-h4 md:text-h4-lg text-brand-white mb-2"><?php echo esc_html($cta_heading); ?></h3>
					<p class="font-body text-body-sm text-brand-white/90"><?php echo esc_html($cta_body); ?></p>
				</div>
				<?php if ($cta_url !== '') : ?>
					<a href="<?php echo esc_url($cta_url); ?>" target="_blank" rel="noopener noreferrer" class="font-body text-body-sm font-medium text-brand-white underline hover:text-brand-nude focus:outline-none focus:ring-2 focus:ring-brand-white focus:ring-offset-2 focus:ring-offset-brand-indigo">
						<?php esc_html_e('Bekijk onze feed', 'boozed'); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php /* Post cards */ ?>
			<?php foreach ($post_items as $item) : ?>
				<?php
				$tag = $item['link'] !== '' ? 'a' : 'div';
				$href_attr = $item['link'] !== '' ? ' href="' . $item['link'] . '" target="_blank" rel="noopener noreferrer"' : '';
				?>
				<<?php echo $tag; ?> class="instagram-slider__card instagram-slider__card--post shrink-0 w-[280px] md:w-[320px] h-[280px] md:h-[320px] block relative overflow-hidden bg-brand-border"<?php echo $href_attr; ?>>
					<div class="instagram-slider__post-img absolute inset-0">
						<img src="<?php echo esc_url($item['image_url']); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
					</div>
					<?php if ($item['caption'] !== '') : ?>
						<div class="instagram-slider__post-caption absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/70 to-transparent">
							<p class="font-body text-body-sm text-brand-white"><?php echo esc_html($item['caption']); ?></p>
						</div>
					<?php endif; ?>
				</<?php echo $tag; ?>>
			<?php endforeach; ?>
		</div>

		<div class="instagram-slider__nav absolute bottom-4 right-0 flex gap-2 z-10 pointer-events-none md:pointer-events-auto">
				<?php \App\Components::render('slider-arrows', [
					'prev_label' => __('Previous', 'boozed'),
					'next_label' => __('Next', 'boozed'),
					'class'      => 'instagram-slider__arrows',
				]); ?>
		</div>
	</div>
</section>

<style>
.instagram-slider__track::-webkit-scrollbar { display: none; }
.instagram-slider__wrap { padding-bottom: 3rem; }
.instagram-slider__nav { margin-bottom: 0.25rem; }
.instagram-slider__arrows .slider-prev,
.instagram-slider__arrows .slider-next {
	background-color: #0C0A21 !important;
	border-color: #0C0A21 !important;
	color: #fff !important;
}
.instagram-slider__arrows .slider-prev:hover,
.instagram-slider__arrows .slider-next:hover {
	background-color: rgba(12, 10, 33, 0.9) !important;
	border-color: rgba(12, 10, 33, 0.9) !important;
}
@media (max-width: 767px) {
	.instagram-slider__nav { pointer-events: none; opacity: 0; }
}
</style>

<?php if (empty($GLOBALS['instagram_slider_js'])) : $GLOBALS['instagram_slider_js'] = true; ?>
<script>
(function() {
	function init() {
		var sections = document.querySelectorAll('.instagram-slider');
		sections.forEach(function(section) {
			var track = section.querySelector('[data-instagram-slider-track]');
			var prev = section.querySelector('.slider-prev');
			var next = section.querySelector('.slider-next');
			if (!track || (!prev && !next)) return;

			var cardWidth = 0;
			var cards = track.querySelectorAll('.instagram-slider__card');
			if (cards.length) {
				var first = cards[0];
				var style = window.getComputedStyle(first);
				cardWidth = first.offsetWidth + parseFloat(style.marginRight || 0) + (track.classList.contains('gap-4') ? 16 : 24);
			}

			function scrollBy(amount) {
				track.scrollBy({ left: amount, behavior: 'smooth' });
			}

			if (prev) prev.addEventListener('click', function() { scrollBy(-cardWidth); });
			if (next) next.addEventListener('click', function() { scrollBy(cardWidth); });
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
