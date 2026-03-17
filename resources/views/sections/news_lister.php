<?php

/**
 * News lister section
 * Category taxonomy filters at top, grid of news cards (post type: post), pagination.
 */

$posts_per_page = function_exists('get_sub_field') ? (int) get_sub_field('news_lister_posts_per_page') : 9;
$cursor_label   = function_exists('get_sub_field') ? get_sub_field('news_lister_cursor_label') : '';

$posts_per_page = max(1, min(50, $posts_per_page));
$cursor_label   = $cursor_label !== '' ? $cursor_label : __('Bericht lezen', 'boozed');

$cursor_svg_path  = get_template_directory() . '/assets/images/custom-cursor-shape.svg';
$cursor_svg_url   = get_template_directory_uri() . '/assets/images/custom-cursor-shape.svg';
$cursor_data_uri  = '';
if (is_file($cursor_svg_path)) {
	$cursor_data_uri = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($cursor_svg_path));
}

$category_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
$current_url   = get_permalink();

$paged = 1;
if (isset($_GET['paged']) && is_numeric($_GET['paged'])) {
	$paged = max(1, (int) $_GET['paged']);
}

$query_args = [
	'post_type'      => 'post',
	'posts_per_page' => $posts_per_page,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
];

if ($category_slug !== '') {
	$query_args['tax_query'] = [
		[
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => $category_slug,
		],
	];
}

$news_query  = new \WP_Query($query_args);
$has_posts   = $news_query->have_posts();
$categories  = get_terms(['taxonomy' => 'category', 'hide_empty' => true]);
$filter_query_args = $category_slug !== '' ? ['category' => $category_slug] : [];
?>

<section class="news-lister max-w-section mx-auto px-4 md:px-section-x py-section-y">
	<div id="nl-filters-track" class="nl-filters-track -mx-4 md:mx-0 overflow-x-auto overflow-y-hidden [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden cursor-grab active:cursor-grabbing touch-pan-x">
		<nav class="nl-filters flex flex-nowrap items-center gap-4 md:gap-6 pb-8 md:pb-10 px-4 md:px-0 md:flex-wrap" aria-label="<?php esc_attr_e('News categories', 'boozed'); ?>">
		<a href="<?php echo esc_url($current_url); ?>" class="font-body text-body-sm font-medium no-underline transition-colors <?php echo $category_slug === '' ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
			<?php esc_html_e('Alles', 'boozed'); ?>
		</a>
		<?php if ($categories && ! is_wp_error($categories)) : ?>
			<?php foreach ($categories as $term) : ?>
				<a href="<?php echo esc_url(add_query_arg('category', $term->slug, $current_url)); ?>" class="font-body text-body-sm font-medium no-underline transition-colors <?php echo $category_slug === $term->slug ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
					<?php echo esc_html($term->name); ?>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>
		</nav>
	</div>

	<?php if ($has_posts) : ?>
		<div class="nl-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
			<?php
			while ($news_query->have_posts()) {
				$news_query->the_post();
				$thumb_id = get_post_thumbnail_id(get_the_ID());
				$img_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
				$excerpt  = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 25);
				\App\Components::render('news-card', [
					'permalink'      => get_permalink(),
					'title'          => get_the_title(),
					'excerpt'        => $excerpt,
					'category_label' => '',
					'image_url'      => $img_url ?: '',
				]);
			}
			wp_reset_postdata();
			?>
		</div>

		<?php
		if ($news_query->max_num_pages > 1) {
			$query      = $news_query;
			$query_args = $filter_query_args;
			include get_template_directory() . '/resources/views/partials/pagination.php';
		}
		?>
	<?php else : ?>
		<p class="font-body text-body text-brand-black/60"><?php esc_html_e('Geen berichten gevonden.', 'boozed'); ?></p>
	<?php endif; ?>

	<?php if ($has_posts) : ?>
	<!-- Custom cursor: custom-cursor-shape.svg + label. Moved to body by JS. -->
	<div id="nl-custom-cursor" class="pointer-events-none fixed z-[9999] flex items-center justify-center" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-white font-body text-body-xs font-medium text-center leading-tight px-2"><?php echo esc_html($cursor_label); ?></span>
	</div>
	<?php endif; ?>
</section>

<?php if (empty($GLOBALS['boozed_news_lister_script_printed'])) : $GLOBALS['boozed_news_lister_script_printed'] = true;
	$nl_script = <<<JSEOF
(function(){
function init(){
var section = document.querySelector('.news-lister');
if(!section) return;
var grids = document.querySelectorAll('.news-lister .nl-grid');
var cards = document.querySelectorAll('.news-lister .news-card');
var cursor = document.getElementById('nl-custom-cursor');
var filtersTrack = document.getElementById('nl-filters-track');

if(cursor && cards.length){
	if(cursor.parentNode !== document.body) document.body.appendChild(cursor);
	var cursorActive = false;
	var hideCursorTimeout = null;
	function cancelHide(){ if(hideCursorTimeout){ clearTimeout(hideCursorTimeout); hideCursorTimeout = null; } }
	function showCursor(){
		cancelHide();
		if(typeof gsap !== 'undefined') gsap.killTweensOf(cursor);
		if(cursorActive) return;
		cursorActive = true;
		cursor.style.display = 'flex';
		if(typeof gsap !== 'undefined'){
			gsap.fromTo(cursor, { scale: 0.4, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.3, ease: 'back.out(1.7)' });
		} else { cursor.style.opacity = '1'; }
	}
	function hideCursor(){
		cancelHide();
		cursorActive = false;
		if(typeof gsap !== 'undefined'){
			gsap.to(cursor, { scale: 0.4, opacity: 0, duration: 0.2, ease: 'power2.in', onComplete: function(){ cursor.style.display = 'none'; } });
		} else { cursor.style.display = 'none'; }
	}
	function moveCursor(e){
		if(typeof gsap !== 'undefined') gsap.to(cursor, { x: e.clientX - 37, y: e.clientY - 37, duration: 0.15, ease: 'power2.out', overwrite: 'auto' });
		else { cursor.style.left = (e.clientX - 37) + 'px'; cursor.style.top = (e.clientY - 37) + 'px'; }
	}
	grids.forEach(function(grid){
		grid.addEventListener('mouseenter', function(ev){ showCursor(); moveCursor(ev); });
		grid.addEventListener('mouseleave', function(){ hideCursorTimeout = setTimeout(hideCursor, 80); });
		grid.addEventListener('mousemove', function(ev){ if(!cursorActive) showCursor(); moveCursor(ev); });
	});
}

if(filtersTrack){
	var isDown = false, startX = 0, scrollLeft = 0;
	filtersTrack.addEventListener('mousedown', function(e){
		isDown = true;
		startX = e.pageX;
		scrollLeft = filtersTrack.scrollLeft;
		filtersTrack.style.cursor = 'grabbing';
		e.preventDefault();
	});
	document.addEventListener('mousemove', function(e){
		if(!isDown) return;
		filtersTrack.scrollLeft = scrollLeft - (e.pageX - startX);
	});
	document.addEventListener('mouseup', function(){
		if(!isDown) return;
		isDown = false;
		filtersTrack.style.cursor = 'grab';
	});
	var touchX = 0, touchY = 0, touchScroll = 0, touchDirection = null;
	filtersTrack.addEventListener('touchstart', function(e){
		touchX = e.touches[0].pageX;
		touchY = e.touches[0].pageY;
		touchScroll = filtersTrack.scrollLeft;
		touchDirection = null;
	}, { passive: true });
	filtersTrack.addEventListener('touchmove', function(e){
		var dx = e.touches[0].pageX - touchX;
		var dy = e.touches[0].pageY - touchY;
		if(!touchDirection){ touchDirection = Math.abs(dx) > Math.abs(dy) ? 'h' : 'v'; }
		if(touchDirection === 'h'){
			e.preventDefault();
			filtersTrack.scrollLeft = touchScroll - dx;
		}
	}, { passive: false });
}

if(typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && cards.length){
	gsap.registerPlugin(ScrollTrigger);
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if(!reducedMotion){
		gsap.fromTo(cards, { opacity: 0, y: 28 }, {
			opacity: 1,
			y: 0,
			duration: 0.55,
			stagger: 0.08,
			ease: 'power2.out',
			scrollTrigger: { trigger: section, start: 'top 85%', toggleActions: 'play none none none' },
			onComplete: function(){ cards.forEach(function(el){ el.style.removeProperty('opacity'); el.style.removeProperty('transform'); }); }
		});
	}
}
}
if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
})();
JSEOF;
	wp_add_inline_script('gsap', $nl_script, 'after');
endif;
?>
<style>
.nl-filters-track { -webkit-overflow-scrolling: touch; }
.nl-filters-track::-webkit-scrollbar { display: none; }
</style>
<?php if ($has_posts) : ?>
<style>
.news-lister .nl-grid,
.news-lister .news-card,
.news-lister .news-card * { cursor: none !important; }
<?php if ($cursor_data_uri !== '') : ?>
.news-lister .nl-grid { cursor: url('<?php echo esc_attr($cursor_data_uri); ?>') 16 16, pointer !important; }
<?php elseif (is_file($cursor_svg_path)) : ?>
.news-lister .nl-grid { cursor: url('<?php echo esc_url($cursor_svg_url); ?>') 16 16, pointer !important; }
<?php endif; ?>
</style>
<?php endif; ?>
