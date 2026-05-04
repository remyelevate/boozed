<?php

/**
 * Projects slider section – supports dynamic projects or custom content (image, tagline, title, link).
 */

$heading        = function_exists('get_sub_field') ? get_sub_field('projects_slider_heading') : 'Onze projecten waar we trots op zijn';
$body           = function_exists('get_sub_field') ? get_sub_field('projects_slider_body') : 'Wij zijn creatieve architecten die jouw verhaal met impact tot leven brengen. Merken zichtbaar en tastbaar maken.';
$btn_label      = function_exists('get_sub_field') ? get_sub_field('projects_slider_button_label') : 'Bekijk al onze projecten';
$btn_url        = function_exists('get_sub_field') ? get_sub_field('projects_slider_button_url') : '';
$cursor_text    = function_exists('get_sub_field') ? get_sub_field('projects_slider_cursor_text') : 'Bekijk project';
$content_source = function_exists('get_sub_field') ? get_sub_field('projects_slider_content_source') : 'projects';

$heading     = $heading ?: 'Onze projecten waar we trots op zijn';
$body        = $body ?: 'Wij zijn creatieve architecten die jouw verhaal met impact tot leven brengen. Merken zichtbaar en tastbaar maken.';
$cursor_text = $cursor_text ?: 'Bekijk project';
$show_btn    = $btn_url && $btn_label;

$items = [];

if ($content_source === 'custom') {
	$raw_items = function_exists('get_sub_field') ? get_sub_field('projects_slider_items') : [];
	if (is_array($raw_items)) {
		foreach ($raw_items as $row) {
			$img_id = isset($row['image']) ? (int) $row['image'] : 0;
			$items[] = [
				'image_url' => $img_id ? wp_get_attachment_image_url($img_id, 'full') : '',
				'tagline'   => isset($row['tagline']) ? (string) $row['tagline'] : '',
				'title'     => isset($row['title']) ? (string) $row['title'] : '',
				'link'      => isset($row['link']) ? esc_url($row['link']) : '',
				'link_text' => isset($row['link_text']) && (string) $row['link_text'] !== '' ? (string) $row['link_text'] : '',
			];
		}
	}
} else {
	$projects_query = new \WP_Query([
		'post_type'      => 'project',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	]);
	while ($projects_query->have_posts()) {
		$projects_query->the_post();
		$terms    = get_the_terms(get_the_ID(), 'project_type');
		$tag_name = ($terms && ! is_wp_error($terms) && ! empty($terms)) ? $terms[0]->name : '';
		$thumb_id = get_post_thumbnail_id();
		if (! $thumb_id && function_exists('get_field')) {
			$gallery = get_field('gallery', get_the_ID());
			if (is_array($gallery) && ! empty($gallery)) {
				$thumb_id = (int) $gallery[0];
			}
		}
		$items[] = [
			'image_url' => $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '',
			'tagline'   => $tag_name,
			'title'     => get_the_title(),
			'link'      => get_permalink(),
			'link_text' => __('Lees meer', 'boozed'),
		];
	}
	wp_reset_postdata();
}

$has_items = ! empty($items);

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>

<section class="py-10 md:py-section-y overflow-hidden max-w-section mx-auto">
	<!-- Single horizontal track: copy card first, then project cards -->
	<div class="ps-track flex flex-row flex-nowrap gap-4 overflow-x-auto overflow-y-hidden cursor-grab" id="ps-track">
		<!-- Copy card (first slide) – design 494×533 -->
		<div class="shrink-0 flex flex-col justify-center bg-brand-indigo p-8 md:p-12 w-[90vw] md:w-[494px] aspect-[494/533]" id="ps-copy">
			<h2 class="font-heading font-bold text-h2 md:text-h2-lg text-brand-white mb-4 md:mb-6"><?php echo esc_html($heading); ?></h2>
			<p class="font-body text-body-md text-brand-white/80 mb-6 md:mb-8"><?php echo esc_html($body); ?></p>
			<?php if ($show_btn) : ?>
				<div class="w-fit">
					<?php \App\Components::render('button', [
						'variant'         => 'coral',
						'label'           => $btn_label,
						'href'            => $btn_url,
						'icon_right_html' => $phosphor_chevron_right,
						'class'           => '!bg-brand-coral',
					]); ?>
				</div>
			<?php endif; ?>
		</div>

		<!-- Project cards -->
		<?php foreach ($items as $item) : ?>
		<?php if ($item['link'] && $item['link_text'] !== '') : ?>
		<a href="<?php echo esc_url($item['link']); ?>" class="ps-card group shrink-0 block relative no-underline cursor-none w-[90vw] md:w-[494px] aspect-[494/533]" draggable="false">
			<div class="w-full h-full overflow-hidden bg-brand-border">
				<?php if (! empty($item['image_url'])) : ?>
					<img src="<?php echo esc_url($item['image_url']); ?>" alt="" class="w-full h-full object-cover pointer-events-none select-none transition-transform duration-300 group-hover:scale-105" draggable="false" style="-webkit-user-drag: none;">
				<?php endif; ?>
			</div>
			<div class="absolute bottom-0 left-0 w-full bg-white p-4 pt-5 opacity-0 invisible translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
				<?php if ($item['tagline'] !== '') : ?>
					<span class="block font-body text-body-sm text-brand-indigo/60 font-medium"><?php echo esc_html($item['tagline']); ?></span>
				<?php endif; ?>
				<h3 class="font-heading font-bold text-2xl leading-tight text-brand-purple mt-1"><?php echo esc_html($item['title']); ?></h3>
				<span class="inline-block font-body text-body-sm text-brand-coral underline mt-2"><?php echo esc_html($item['link_text']); ?></span>
			</div>
		</a>
		<?php else : ?>
		<div class="ps-card group shrink-0 block relative cursor-none w-[90vw] md:w-[494px] aspect-[494/533]" draggable="false">
			<div class="w-full h-full overflow-hidden bg-brand-border">
				<?php if (! empty($item['image_url'])) : ?>
					<img src="<?php echo esc_url($item['image_url']); ?>" alt="" class="w-full h-full object-cover pointer-events-none select-none transition-transform duration-300 group-hover:scale-105" draggable="false" style="-webkit-user-drag: none;">
				<?php endif; ?>
			</div>
			<div class="absolute bottom-0 left-0 w-full bg-white p-4 pt-5 opacity-0 invisible translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
				<?php if ($item['tagline'] !== '') : ?>
					<span class="block font-body text-body-sm text-brand-indigo/60 font-medium"><?php echo esc_html($item['tagline']); ?></span>
				<?php endif; ?>
				<h3 class="font-heading font-bold text-2xl leading-tight text-brand-purple mt-1"><?php echo esc_html($item['title']); ?></h3>
				<?php if ($item['link_text'] !== '') : ?>
					<span class="inline-block font-body text-body-sm text-brand-coral underline mt-2"><?php echo esc_html($item['link_text']); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php endforeach; ?>

		<!-- End spacer so the last card can scroll fully into view -->
		<div class="shrink-0 w-4" aria-hidden="true"></div>
	</div>

	<!-- Cursor (arrows icon, same as blog lister) -->
	<div class="fixed left-0 top-0 w-[75px] h-[75px] pointer-events-none z-[9999] flex items-center justify-center" id="ps-cursor" style="display:none;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
				<path d="M224 128a8 8 0 0 1-2.34 5.66l-32 32a8 8 0 0 1-11.32-11.32L200.69 128l-22.35-22.34a8 8 0 0 1 11.32-11.32l32 32A8 8 0 0 1 224 128ZM32 128a8 8 0 0 0 2.34 5.66l32 32a8 8 0 0 0 11.32-11.32L55.31 128l22.35-22.34a8 8 0 0 0-11.32-11.32l-32 32A8 8 0 0 0 32 128Z"/>
			</svg>
		</span>
	</div>
</section>

<style>
.ps-track { scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
.ps-track::-webkit-scrollbar { display: none; }
</style>

<?php if ($has_items && empty($GLOBALS['ps_js'])) : $GLOBALS['ps_js'] = true; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var track  = document.getElementById('ps-track');
	var cards  = document.querySelectorAll('.ps-card');
	var cursor = document.getElementById('ps-cursor');

	if (!track) return;

	// Custom cursor – bound to track so it stays visible when moving between cards
	if (cursor && track) {
		var cursorActive = false;

		function showCursor() {
			if (cursorActive) return;
			cursorActive = true;
			cursor.style.display = 'flex';
			if (typeof gsap !== 'undefined') {
				gsap.fromTo(cursor, { scale: 0.4, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.3, ease: 'back.out(1.7)' });
			} else {
				cursor.style.opacity = '1';
			}
		}

		function hideCursor() {
			cursorActive = false;
			if (typeof gsap !== 'undefined') {
				gsap.to(cursor, {
					scale: 0.4,
					opacity: 0,
					duration: 0.2,
					ease: 'power2.in',
					onComplete: function() { cursor.style.display = 'none'; }
				});
			} else {
				cursor.style.opacity = '0';
				cursor.style.display = 'none';
			}
		}

		function moveCursor(e) {
			if (typeof gsap !== 'undefined') {
				gsap.to(cursor, { x: e.clientX - 37, y: e.clientY - 37, duration: 0.15, ease: 'power2.out' });
			} else {
				cursor.style.left = (e.clientX - 37) + 'px';
				cursor.style.top  = (e.clientY - 37) + 'px';
			}
		}

		track.addEventListener('mouseenter', showCursor);
		track.addEventListener('mouseleave', hideCursor);
		track.addEventListener('mousemove', moveCursor);
	}

	// Drag to scroll
	var isDown = false, startX = 0, scrollLeft = 0, didDrag = false;

	track.addEventListener('mousedown', function(e) {
		isDown = true;
		didDrag = false;
		startX = e.pageX;
		scrollLeft = track.scrollLeft;
		track.style.cursor = 'grabbing';
		e.preventDefault();
	});

	document.addEventListener('mousemove', function(e) {
		if (!isDown) return;
		var walk = e.pageX - startX;
		if (Math.abs(walk) > 5) didDrag = true;
		track.scrollLeft = scrollLeft - walk;
	});

	document.addEventListener('mouseup', function() {
		if (!isDown) return;
		isDown = false;
		track.style.cursor = 'grab';
	});

	cards.forEach(function(card) {
		card.addEventListener('click', function(e) {
			if (didDrag) {
				e.preventDefault();
				e.stopPropagation();
			}
		}, true);
	});

	// Touch – capture only clear horizontal swipes, let vertical body scroll pass through
	var touchX = 0, touchY = 0, touchScroll = 0, touchDirection = null;
	track.addEventListener('touchstart', function(e) {
		touchX = e.touches[0].pageX;
		touchY = e.touches[0].pageY;
		touchScroll = track.scrollLeft;
		touchDirection = null;
	}, { passive: true });
	track.addEventListener('touchmove', function(e) {
		var dx = e.touches[0].pageX - touchX;
		var dy = e.touches[0].pageY - touchY;
		if (!touchDirection) {
			var adx = Math.abs(dx);
			var ady = Math.abs(dy);
			// Require a minimum movement and clear horizontal intent before locking.
			if (adx < 8 && ady < 8) return;
			touchDirection = (adx > ady * 1.2) ? 'h' : 'v';
		}
		if (touchDirection === 'h') {
			e.preventDefault();
			track.scrollLeft = touchScroll - dx;
		}
	}, { passive: false });
});
</script>
<?php endif; ?>
