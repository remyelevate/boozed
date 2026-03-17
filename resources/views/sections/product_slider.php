<?php

/**
 * Product slider section – "Onze populaire items" style.
 * Products can be selected by tags, collection (category), or manually.
 * Uses product-card partial for each item; slider with prev/next arrows and "Bekijk alle producten" CTA.
 */

$heading    = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_heading') : '';
$btn_label  = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_button_label') : '';
$btn_url    = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_button_url') : '';
$link_text  = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_link_text') : '';
$source     = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_source') : 'manual';
$limit      = function_exists('get_sub_field') ? max(1, min(48, (int) get_sub_field('product_slider_limit'))) : 12;
$search_url = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_search_url') : '';
$search_placeholder = function_exists('get_sub_field') ? (string) get_sub_field('product_slider_search_placeholder') : '';

$heading   = $heading ?: __('Onze populaire items', 'boozed');
$btn_label = $btn_label ?: __('Bekijk alle producten', 'boozed');
$show_btn  = $btn_url !== '' && $btn_label !== '';
$search_placeholder = $search_placeholder ?: __('Online catalogus (meer dan 2000 producten)', 'boozed');
if ($search_url === '' && function_exists('wc_get_page_permalink')) {
	$search_url = wc_get_page_permalink('shop');
}
if ($search_url === '') {
	$search_url = home_url('/');
}
$show_search = true;

$product_ids = [];
$post_type   = 'product';

if ($source === 'manual') {
	$raw = function_exists('get_sub_field') ? get_sub_field('product_slider_products') : [];
	if (is_array($raw)) {
		$product_ids = array_map('intval', $raw);
	}
	$product_ids = array_filter(array_unique($product_ids));
} else {
	$term_ids = [];
	if ($source === 'tags') {
		$raw = function_exists('get_sub_field') ? get_sub_field('product_slider_tag_terms') : [];
	} else {
		$raw = function_exists('get_sub_field') ? get_sub_field('product_slider_collection_terms') : [];
	}
	if (is_array($raw)) {
		$term_ids = array_map('intval', $raw);
	} elseif (is_numeric($raw)) {
		$term_ids = [(int) $raw];
	}
	$term_ids = array_filter($term_ids);
	if (empty($term_ids)) {
		$product_ids = [];
	} else {
		$taxonomy = $source === 'tags' ? 'product_tag' : 'product_cat';
		$query_args = [
			'post_type'      => $post_type,
			'posts_per_page' => $limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'tax_query'      => [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_ids,
				],
			],
		];
		$q = new \WP_Query($query_args);
		$product_ids = $q->posts ? array_map('intval', $q->posts) : [];
		wp_reset_postdata();
	}
}

$show_prices = is_user_logged_in() && function_exists('wc_get_product');
$items = [];
foreach ($product_ids as $pid) {
	$post = get_post($pid);
	if ( ! $post || $post->post_type !== $post_type || $post->post_status !== 'publish') {
		continue;
	}
	$thumb_id = get_post_thumbnail_id($pid);
	$img_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
	$category = '';
	if (taxonomy_exists('product_cat')) {
		$terms = get_the_terms($pid, 'product_cat');
		if ($terms && ! is_wp_error($terms) && ! empty($terms)) {
			$category = $terms[0]->name;
		}
	}
	$item = [
		'image_url'      => $img_url ?: '',
		'image_alt'      => get_the_title($pid),
		'category_label' => $category,
		'title'          => get_the_title($pid),
		'url'            => get_permalink($pid),
		'link_text'      => $link_text,
	];
	if ($show_prices) {
		$wc_product = wc_get_product($pid);
		if ($wc_product && $wc_product->get_price_html()) {
			$item['price_html'] = $wc_product->get_price_html();
		}
	}
	$items[] = $item;
}

$has_items = ! empty($items);
$section_id = 'product-slider-' . (function_exists('get_row_index') ? get_row_index() : uniqid());
$cursor_svg_url = get_template_directory_uri() . '/assets/images/custom-cursor-shape.svg';

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>

<section class="product-slider product-slider--full-bleed-right max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y" id="<?php echo esc_attr($section_id); ?>">
	<div class="product-slider__header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 md:mb-8">
		<h2 class="product-slider__heading font-heading font-bold text-h2 md:text-h2-lg text-brand-indigo m-0"><?php echo esc_html($heading); ?></h2>
		<?php if ($show_search) : ?>
			<form class="product-slider__search flex shrink-0" action="<?php echo esc_url($search_url); ?>" method="get" role="search" aria-label="<?php esc_attr_e('Search catalog', 'boozed'); ?>">
				<label for="<?php echo esc_attr($section_id); ?>-search" class="sr-only"><?php esc_attr_e('Search', 'boozed'); ?></label>
				<span class="product-slider__search-icon absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-brand-black/50" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" fill="currentColor"><path d="M229.66 218.34l-50.07-50.06a88.11 88.11 0 1 0-11.32 11.32l50.07 50.06a8 8 0 0 0 11.32-11.32zM40 112a72 72 0 1 1 72 72 72.08 72.08 0 0 1-72-72z"/></svg>
				</span>
				<input type="search" id="<?php echo esc_attr($section_id); ?>-search" name="s" class="product-slider__search-input w-[500px] max-w-full h-12 pl-10 pr-4 border border-brand-black/15 rounded-md font-body text-body-sm text-brand-black placeholder:text-brand-black/50 focus:outline-none focus:ring-2 focus:ring-brand-indigo/30 focus:border-brand-indigo" placeholder="<?php echo esc_attr($search_placeholder); ?>" value="<?php echo esc_attr(get_search_query()); ?>">
			</form>
		<?php endif; ?>
	</div>

	<?php if ($has_items) : ?>
		<div class="product-slider__wrap relative" data-product-slider-wrap>
			<div class="product-slider__track flex gap-4 md:gap-6 overflow-x-auto overflow-y-hidden scroll-smooth scrollbar-width-none -mx-4 px-4 md:mx-0 md:px-0 cursor-grab active:cursor-grabbing items-stretch" data-product-slider-track aria-label="<?php esc_attr_e('Product carousel', 'boozed'); ?>">
				<?php foreach ($items as $item) : ?>
					<div class="product-slider__card shrink-0 w-[280px] md:w-[320px] flex flex-col h-full">
						<?php \App\Components::render('product-card', $item); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($show_btn) : ?>
		<div class="product-slider__cta mt-8">
			<?php \App\Components::render('button', [
				'variant'          => 'coral',
				'label'            => $btn_label,
				'href'             => $btn_url,
				'icon_right_html'  => $phosphor_chevron_right,
				'class'            => '!bg-brand-coral',
			]); ?>
		</div>
	<?php endif; ?>

	<?php if ($has_items && empty($GLOBALS['product_slider_cursor_printed'])) : $GLOBALS['product_slider_cursor_printed'] = true; ?>
		<div id="product-slider-custom-cursor" class="product-slider__cursor pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
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
</section>

<style>
.product-slider__search { position: relative; }
.product-slider__track::-webkit-scrollbar { display: none; }
/* Extend slider to viewport right edge only (content stays left-aligned) */
.product-slider--full-bleed-right { overflow: visible; }
.product-slider--full-bleed-right .product-slider__wrap {
	width: calc((100vw + 100%) / 2);
	max-width: 100vw;
	padding-right: 1.5rem;
	box-sizing: border-box;
}
/* Equal height tiles: cards stretch and content area fills */
.product-slider__card .product-card {
	display: flex;
	flex-direction: column;
	flex: 1;
	min-height: 0;
}
.product-slider__card .product-card .product-card__content {
	flex: 1;
	display: flex;
	flex-direction: column;
}
.product-slider__card .product-card .product-card__title {
	flex: 1;
}
/* Custom cursor: hide default cursor over the slider track */
.product-slider__wrap[data-product-slider-wrap],
.product-slider__wrap[data-product-slider-wrap] .product-slider__track,
.product-slider__wrap[data-product-slider-wrap] .product-slider__track * {
	cursor: none;
}
#product-slider-custom-cursor {
	transform: translate(-50%, -50%);
}
</style>

<?php if ($has_items && empty($GLOBALS['product_slider_js'])) : $GLOBALS['product_slider_js'] = true; ?>
<script>
(function() {
	function init() {
		var sections = document.querySelectorAll('.product-slider');
		var cursor = document.getElementById('product-slider-custom-cursor');
		var cursorActive = false;
		var hideCursorTimeout = null;
		var HIDE_DELAY_MS = 120;

		if (cursor && cursor.parentNode !== document.body) {
			document.body.appendChild(cursor);
		}

		function cancelHideCursor() {
			if (hideCursorTimeout) {
				clearTimeout(hideCursorTimeout);
				hideCursorTimeout = null;
			}
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

		sections.forEach(function(section) {
			var wrap = section.querySelector('[data-product-slider-wrap]');
			var track = section.querySelector('[data-product-slider-track]');
			var prev = section.querySelector('.slider-prev');
			var next = section.querySelector('.slider-next');
			if (!track) return;

			/* Custom cursor over slider wrap */
			if (cursor && wrap) {
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

			/* Prev/next arrows */
			if (prev || next) {
				var cardWidth = 0;
				var cards = track.querySelectorAll('.product-slider__card');
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
			}

			/* Drag to scroll */
			var isDown = false, startX = 0, scrollLeftStart = 0, didDrag = false;
			track.addEventListener('mousedown', function(e) {
				isDown = true;
				didDrag = false;
				startX = e.pageX;
				scrollLeftStart = track.scrollLeft;
				e.preventDefault();
			});
			document.addEventListener('mousemove', function(e) {
				if (!isDown) return;
				var walk = e.pageX - startX;
				if (Math.abs(walk) > 5) didDrag = true;
				track.scrollLeft = scrollLeftStart - walk;
			});
			document.addEventListener('mouseup', function() {
				isDown = false;
			});

			/* Prevent card link when user dragged */
			var cardLinks = track.querySelectorAll('.product-slider__card > a');
			cardLinks.forEach(function(link) {
				link.addEventListener('click', function(e) {
					if (didDrag) e.preventDefault();
				});
			});
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
