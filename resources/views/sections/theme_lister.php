<?php

/**
 * Theme lister section
 * Filter categories nav, grid of thema CPT cards (image, title, description + "Lees meer"). Custom cursor on each card (blob + label).
 */

$posts_per_page = function_exists('get_sub_field') ? (int) get_sub_field('theme_lister_posts_per_page') : 9;
$cursor_text    = function_exists('get_sub_field') ? get_sub_field('theme_lister_cursor_text') : '';

$cursor_text = $cursor_text ?: __('Thema bekijken', 'boozed');
$posts_per_page = max(1, min(24, $posts_per_page));

$current_url = is_post_type_archive('thema') ? get_post_type_archive_link('thema') : get_permalink();
$filter_slug = isset($_GET['thema_cat']) ? sanitize_text_field(wp_unslash($_GET['thema_cat'])) : '';

$query_args = [
	'post_type'      => 'thema',
	'posts_per_page' => $posts_per_page,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
];

if ($filter_slug && taxonomy_exists('thema_categorie')) {
	$query_args['tax_query'] = [
		[
			'taxonomy' => 'thema_categorie',
			'field'    => 'slug',
			'terms'    => $filter_slug,
		],
	];
}

$themes_query = new \WP_Query($query_args);
$themes = $themes_query->posts;
$has_themes = !empty($themes);

$filter_terms = taxonomy_exists('thema_categorie') ? get_terms([ 'taxonomy' => 'thema_categorie', 'hide_empty' => true ]) : [];
if (is_wp_error($filter_terms)) {
	$filter_terms = [];
}
?>

<section class="theme-lister max-w-section mx-auto px-4 md:px-section-x py-section-y">
	<nav class="tl-filters flex items-center gap-6 pb-8 md:pb-10" aria-label="<?php esc_attr_e('Thema filters', 'boozed'); ?>">
		<a href="<?php echo esc_url($current_url); ?>" class="font-body text-body-sm font-medium no-underline transition-colors <?php echo $filter_slug === '' ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
			<?php esc_html_e('Alles', 'boozed'); ?>
		</a>
		<?php if (!empty($filter_terms)) : ?>
			<?php foreach ($filter_terms as $term) : ?>
				<a href="<?php echo esc_url(add_query_arg('thema_cat', $term->slug, $current_url)); ?>" class="font-body text-body-sm font-medium no-underline transition-colors <?php echo $filter_slug === $term->slug ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
					<?php echo esc_html($term->name); ?>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>
	</nav>

	<?php if ($has_themes) : ?>
		<?php if (empty($GLOBALS['theme_lister_cursor_printed'])) : $GLOBALS['theme_lister_cursor_printed'] = true; ?>
		<div id="tl-custom-cursor" class="tl-custom-cursor pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
			<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
			</svg>
			<span class="absolute inset-0 flex items-center justify-center text-brand-white font-body text-body-xs font-medium leading-tight text-center px-2"><?php echo esc_html($cursor_text); ?></span>
		</div>
		<?php endif; ?>
		<div class="tl-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" role="list">
			<?php
			foreach ($themes as $post) {
				setup_postdata($post);
				$permalink   = function_exists('boozed_thema_card_url') ? boozed_thema_card_url($post->ID) : get_permalink($post);
				$title       = get_the_title($post);
				$excerpt     = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(get_the_content(null, false, $post), 25);
				$thumb_id    = get_post_thumbnail_id($post);
				$featured_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
				?>
				<a href="<?php echo esc_url($permalink); ?>" class="tl-card group flex flex-col rounded-lg overflow-hidden bg-transparent no-underline text-inherit outline-none focus:outline-none [&_*]:!cursor-none cursor-none" role="listitem">
					<div class="tl-card__img aspect-[4/3] overflow-hidden bg-brand-border">
						<?php if ($featured_url) : ?>
							<img src="<?php echo esc_url($featured_url); ?>" alt="" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
						<?php endif; ?>
					</div>
					<div class="tl-card__content pt-4 flex flex-col flex-1">
						<h3 class="font-heading font-bold text-h4 md:text-h4-lg text-brand-purple mb-2"><?php echo esc_html($title); ?></h3>
						<p class="font-body text-body text-brand-black line-clamp-3">
							<?php echo esc_html($excerpt); ?>
							<span class="font-body text-body text-brand-black/80 underline"><?php esc_html_e('Lees meer', 'boozed'); ?></span>
						</p>
					</div>
				</a>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	<?php endif; ?>
</section>

<?php if ($has_themes && empty($GLOBALS['theme_lister_style_printed'])) : $GLOBALS['theme_lister_style_printed'] = true; ?>
<style>
.theme-lister .tl-card,
.theme-lister .tl-card * {
	cursor: none;
}
#tl-custom-cursor {
	transform: translate(-50%, -50%);
}
</style>
<?php endif; ?>

<?php if ($has_themes && empty($GLOBALS['theme_lister_script_printed'])) : $GLOBALS['theme_lister_script_printed'] = true; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var cursor = document.getElementById('tl-custom-cursor');
	var cursorActive = false;
	var hideCursorTimeout = null;
	var HIDE_DELAY_MS = 120;

	if (cursor && window.matchMedia('(pointer: fine)').matches) {
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
		document.querySelectorAll('.theme-lister').forEach(function(section) {
			var cards = section.querySelectorAll('.tl-card');
			cards.forEach(function(card) {
				card.addEventListener('mouseenter', function(e) {
					cancelHideCursor();
					showCursor();
					moveCursor(e);
				});
				card.addEventListener('mouseleave', function() {
					hideCursorTimeout = setTimeout(hideCursor, HIDE_DELAY_MS);
				});
				card.addEventListener('mousemove', moveCursor);
			});
		});
	}
});
</script>
<?php endif; ?>
