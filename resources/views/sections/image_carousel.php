<?php

/**
 * Image carousel section – horizontal scrollable gallery with custom cursor (arrows icon + custom-cursor-shape.svg).
 * Same cursor as projects_slider: blob shape with arrows icon.
 */

$raw_items = function_exists('get_sub_field') ? get_sub_field('image_carousel_images') : [];
$items     = [];

if (is_array($raw_items)) {
	foreach ($raw_items as $row) {
		$img_id = isset($row['image']) ? (int) $row['image'] : 0;
		if (!$img_id) continue;
		$img_url = wp_get_attachment_image_url($img_id, 'large');
		if (!$img_url) continue;
		$items[] = ['image_url' => $img_url];
	}
}

$has_items   = ! empty($items);
$section_idx = function_exists('get_row_index') ? get_row_index() : uniqid();
$track_id    = 'ic-track-' . $section_idx;
$cursor_id   = 'ic-cursor-' . $section_idx;
?>

<section class="max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y overflow-hidden" id="ic-section-<?php echo esc_attr($section_idx); ?>">
	<div class="ic-track flex flex-row flex-nowrap gap-4 overflow-x-auto overflow-y-hidden cursor-grab touch-pan-x" id="<?php echo esc_attr($track_id); ?>">
		<?php foreach ($items as $item) : ?>
		<div class="ic-card shrink-0 block relative cursor-none w-[90vw] md:w-[494px] aspect-[494/533]" draggable="false">
			<div class="w-full h-full overflow-hidden bg-brand-border">
				<img src="<?php echo esc_url($item['image_url']); ?>" alt="" class="w-full h-full object-cover pointer-events-none select-none" draggable="false" style="-webkit-user-drag: none;" loading="lazy">
			</div>
		</div>
		<?php endforeach; ?>
		<div class="shrink-0 w-4" aria-hidden="true"></div>
	</div>

	<!-- Custom cursor (arrows icon, same as projects_slider) – uses custom-cursor-shape.svg -->
	<div class="fixed left-0 top-0 w-[75px] h-[75px] pointer-events-none z-[9999] flex items-center justify-center" id="<?php echo esc_attr($cursor_id); ?>" style="display:none;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white">
			<?php
			$cursor_svg_path = get_template_directory() . '/assets/images/custom-cursor-shape.svg';
			if (file_exists($cursor_svg_path)) {
				$arrows_svg = file_get_contents($cursor_svg_path);
				$arrows_svg = str_replace('fill="#0C0A21"', 'fill="currentColor"', $arrows_svg);
				echo $arrows_svg;
			} else {
				?>
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
					<path d="M224 128a8 8 0 0 1-2.34 5.66l-32 32a8 8 0 0 1-11.32-11.32L200.69 128l-22.35-22.34a8 8 0 0 1 11.32-11.32l32 32A8 8 0 0 1 224 128ZM32 128a8 8 0 0 0 2.34 5.66l32 32a8 8 0 0 0 11.32-11.32L55.31 128l22.35-22.34a8 8 0 0 0-11.32-11.32l-32 32A8 8 0 0 0 32 128Z"/>
				</svg>
				<?php
			}
			?>
		</span>
	</div>
</section>

<style>
.ic-track { scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
.ic-track::-webkit-scrollbar { display: none; }
</style>

<?php if ($has_items && empty($GLOBALS['ic_js'])) : $GLOBALS['ic_js'] = true; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var sections = document.querySelectorAll('.ic-track');
	sections.forEach(function(track) {
		var cursor = document.getElementById(track.id.replace('ic-track-', 'ic-cursor-'));
		if (!track || !cursor) return;

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
	});

	// Drag to scroll (per track)
	sections.forEach(function(track) {
		var isDown = false, startX = 0, scrollLeft = 0;

		track.addEventListener('mousedown', function(e) {
			isDown = true;
			startX = e.pageX;
			scrollLeft = track.scrollLeft;
			track.style.cursor = 'grabbing';
			e.preventDefault();
		});

		document.addEventListener('mousemove', function(e) {
			if (!isDown) return;
			track.scrollLeft = scrollLeft - (e.pageX - startX);
		});

		document.addEventListener('mouseup', function() {
			if (!isDown) return;
			isDown = false;
			track.style.cursor = 'grab';
		});
	});

	// Touch – horizontal swipes
	sections.forEach(function(track) {
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
				touchDirection = Math.abs(dx) > Math.abs(dy) ? 'h' : 'v';
			}
			if (touchDirection === 'h') {
				e.preventDefault();
				track.scrollLeft = touchScroll - dx;
			}
		}, { passive: false });
	});
});
</script>
<?php endif; ?>
