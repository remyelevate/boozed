<?php

/**
 * Blog lister section
 * Heading, "Meer inspiratie?" link, fluid horizontal carousel of blog items. Each item = row with text (left) | featured image (right), text 426px | image 486px.
 * Custom cursor over the track.
 */

$heading        = function_exists('get_sub_field') ? get_sub_field('blog_lister_heading') : 'Achter de schermen & vooraan in trends';
$more_label     = function_exists('get_sub_field') ? get_sub_field('blog_lister_more_label') : 'Meer inspiratie?';
$more_url       = function_exists('get_sub_field') ? get_sub_field('blog_lister_more_url') : '';
$posts_per_page = function_exists('get_sub_field') ? (int) get_sub_field('blog_lister_posts_per_page') : 8;

$heading        = $heading ?: 'Achter de schermen & vooraan in trends';
$more_label     = $more_label ?: 'Meer inspiratie?';
$show_more      = $more_url && $more_label;

$cursor_svg_url   = get_template_directory_uri() . '/assets/images/custom-cursor-shape.svg';
$cursor_svg_path  = get_template_directory() . '/assets/images/custom-cursor-shape.svg';
$cursor_data_uri  = '';
if (file_exists($cursor_svg_path)) {
	$cursor_data_uri = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($cursor_svg_path));
}

$posts_query = new \WP_Query([
	'post_type'      => 'post',
	'posts_per_page' => max(2, min(20, $posts_per_page)),
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
]);
$posts = $posts_query->posts;
$has_posts = !empty($posts);

?>

<section class="blog-lister max-w-section mx-auto px-section-x py-section-y">
	<header class="bl-header flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8 md:mb-12">
		<h2 class="font-heading font-bold text-h2 md:text-h2-lg text-brand-black"><?php echo esc_html($heading); ?></h2>
		<?php if ($show_more) : ?>
			<a href="<?php echo esc_url($more_url); ?>" class="font-body text-body-md text-brand-purple hover:underline shrink-0"><?php echo esc_html($more_label); ?></a>
		<?php endif; ?>
	</header>

	<?php if ($has_posts) : ?>
		<?php if (empty($GLOBALS['blog_lister_cursor_printed'])) : $GLOBALS['blog_lister_cursor_printed'] = true; ?>
		<!-- Custom cursor: follows mouse over track; uses custom-cursor-shape.svg -->
		<div id="bl-custom-cursor" class="bl-custom-cursor pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
			<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
			</svg>
			<span class="absolute inset-0 flex items-center justify-center text-brand-white">
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
					<path d="M224 128a8 8 0 0 1-2.34 5.66l-32 32a8 8 0 0 1-11.32-11.32L200.69 128l-22.35-22.34a8 8 0 0 1 11.32-11.32l32 32A8 8 0 0 1 224 128ZM32 128a8 8 0 0 0 2.34 5.66l32 32a8 8 0 0 0 11.32-11.32L55.31 128l22.35-22.34a8 8 0 0 0-11.32-11.32l-32 32A8 8 0 0 0 32 128Z"/>
				</svg>
			</span>
		</div>
		<?php endif; ?>
		<div class="bl-track-wrap overflow-hidden cursor-none [&_*]:!cursor-none">
			<div class="bl-track flex gap-6 overflow-x-auto scrollbar-width-none py-2 [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden" role="region" aria-label="<?php esc_attr_e('Blog posts carousel', 'boozed'); ?>">
				<?php
				foreach ($posts as $post) {
					setup_postdata($post);
					$permalink   = get_permalink($post);
					$title       = get_the_title($post);
					$excerpt     = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(get_the_content(null, false, $post), 25);
					$cats        = get_the_category($post->ID);
					$cat_name    = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
					$thumb_id    = get_post_thumbnail_id($post);
					$featured_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
					?>
					<a href="<?php echo esc_url($permalink); ?>" class="bl-card flex-shrink-0 flex rounded-lg overflow-hidden bg-brand-white border border-brand-border no-underline text-inherit hover:border-brand-purple/30 transition-colors" style="width: var(--bl-slide-width, 912px); height: 486px; min-height: 486px;"<?php echo $featured_url ? ' data-featured-url="' . esc_url($featured_url) . '"' : ''; ?> data-permalink="<?php echo esc_url($permalink); ?>" data-title="<?php echo esc_attr($title); ?>" data-excerpt="<?php echo esc_attr($excerpt); ?>" data-cat="<?php echo esc_attr($cat_name); ?>">
						<div class="bl-card-text w-[426px] flex-shrink-0 p-8 flex flex-col justify-center">
							<?php if ($cat_name) : ?>
								<span class="font-body text-body-sm text-brand-indigo/60 block mb-2"><?php echo esc_html($cat_name); ?></span>
							<?php endif; ?>
							<h3 class="font-heading font-bold text-h4 md:text-h4-lg text-brand-purple mb-3"><?php echo esc_html($title); ?></h3>
							<p class="font-body text-body text-brand-black mb-4 line-clamp-3"><?php echo esc_html($excerpt); ?></p>
							<span class="font-body text-body-sm text-brand-indigo/60 underline"><?php esc_html_e('Lees meer', 'boozed'); ?></span>
						</div>
						<div class="bl-card-img w-[486px] flex-shrink-0 min-h-[486px] overflow-hidden bg-brand-border">
							<?php if ($featured_url) : ?>
								<img src="<?php echo esc_url($featured_url); ?>" alt="" class="w-full h-full object-cover min-h-[486px]" loading="lazy" draggable="false">
							<?php endif; ?>
						</div>
					</a>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if ($has_posts && empty($GLOBALS['blog_lister_style_printed'])) : $GLOBALS['blog_lister_style_printed'] = true; ?>
<style>
/* Custom cursor: hide default cursor over the blog slider track */
.blog-lister .bl-track-wrap,
.blog-lister .bl-track-wrap .bl-track,
.blog-lister .bl-track-wrap .bl-track * {
	cursor: none;
}
.blog-lister .bl-track {
	scroll-snap-type: none;
	scroll-behavior: auto;
	align-items: flex-start;
}
.blog-lister .bl-track > * {
	scroll-snap-align: none;
}
#bl-custom-cursor {
	transform: translate(-50%, -50%);
}
</style>
<?php endif; ?>

<?php if ($has_posts && empty($GLOBALS['blog_lister_script_printed'])) : $GLOBALS['blog_lister_script_printed'] = true; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var cursor = document.getElementById('bl-custom-cursor');
	var cursorActive = false;
	var hideCursorTimeout = null;
	var HIDE_DELAY_MS = 120;

	if (cursor) {
		if (cursor.parentNode !== document.body) document.body.appendChild(cursor);
		function cancelHideCursor() {
			if (hideCursorTimeout) { clearTimeout(hideCursorTimeout); hideCursorTimeout = null; }
		}
		function showCursor() {
			if (cursorActive || !cursor) return;
			cursorActive = true;
			cursor.style.display = 'block';
			if (typeof gsap !== 'undefined') {
				gsap.fromTo(cursor, { scale: 0.4, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.3, ease: 'back.out(1.7)' });
			} else {
				cursor.style.opacity = '1';
			}
		}
		function hideCursor() {
			if (!cursor) return;
			cancelHideCursor();
			cursorActive = false;
			if (typeof gsap !== 'undefined') {
				gsap.to(cursor, { scale: 0.4, opacity: 0, duration: 0.2, ease: 'power2.in', onComplete: function() { cursor.style.display = 'none'; } });
			} else {
				cursor.style.opacity = '0';
				cursor.style.display = 'none';
			}
		}
		function moveCursor(e) {
			if (!cursor) return;
			var x = e.clientX;
			var y = e.clientY;
			if (typeof gsap !== 'undefined') {
				gsap.set(cursor, { left: x + 'px', top: y + 'px' });
			} else {
				cursor.style.left = x + 'px';
				cursor.style.top = y + 'px';
			}
		}
		document.querySelectorAll('.blog-lister').forEach(function(section) {
			var wrap = section.querySelector('.bl-track-wrap');
			if (wrap) {
				wrap.addEventListener('mouseenter', function(e) {
					cancelHideCursor();
					showCursor();
					moveCursor(e);
				});
				wrap.addEventListener('mouseleave', function() {
					hideCursorTimeout = setTimeout(hideCursor, HIDE_DELAY_MS);
				});
				wrap.addEventListener('mousemove', moveCursor);
			}
		});
	}

	document.querySelectorAll('.blog-lister').forEach(function(section) {
		var track = section.querySelector('.bl-track');
		if (!track) return;

		// Desktop drag-to-scroll (pointer: fine = mouse/trackpad, not touch)
		if (window.matchMedia('(pointer: fine)').matches) {
			var wrap = section.querySelector('.bl-track-wrap');
			var dragState = { active: false, startX: 0, startScrollLeft: 0, hasMoved: false };
			var overlay = null;
			track.style.userSelect = 'none';

			track.addEventListener('dragstart', function(e) { e.preventDefault(); }, true);
			track.addEventListener('mousedown', function(e) {
				if (e.button !== 0) return;
				e.preventDefault();
				dragState.active = true;
				dragState.startX = e.clientX;
				dragState.startScrollLeft = track.scrollLeft;
				dragState.hasMoved = false;
			});

			document.addEventListener('mousemove', function(e) {
				if (!dragState.active) return;
				var dx = e.clientX - dragState.startX;
				if (Math.abs(dx) > 5) {
					if (!dragState.hasMoved) {
						dragState.hasMoved = true;
						if (wrap && !overlay) {
							overlay = document.createElement('div');
							overlay.className = 'bl-drag-overlay';
							overlay.style.cssText = 'position:absolute;inset:0;z-index:10;cursor:none;pointer-events:auto;';
							wrap.style.position = 'relative';
							wrap.appendChild(overlay);
						}
					}
				}
				track.scrollLeft = dragState.startScrollLeft - dx;
			});

			function removeOverlay() {
				if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
				overlay = null;
			}

			document.addEventListener('mouseup', function() {
				if (dragState.active) {
					dragState.active = false;
					removeOverlay();
				}
			});
		}
	});
});
</script>
<?php endif; ?>
