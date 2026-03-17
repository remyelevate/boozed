<?php

/**
 * Projects lister section
 * Table layout with row hover showing featured image on the right side of the hovered row.
 */

$is_archive = is_post_type_archive('project');

$heading        = !$is_archive && function_exists('get_sub_field') ? get_sub_field('projects_lister_heading') : '';
$posts_per_page = !$is_archive && function_exists('get_sub_field') ? (int) get_sub_field('projects_lister_posts_per_page') : 10;
$cursor_text    = !$is_archive && function_exists('get_sub_field') ? get_sub_field('projects_lister_cursor_text') : 'Bekijk project';

$posts_per_page = max(1, min(50, $posts_per_page));
$cursor_text    = $cursor_text ?: 'Bekijk project';

$cursor_svg_url  = get_template_directory_uri() . '/assets/images/custom-cursor-shape.svg';
$cursor_svg_path = get_template_directory() . '/assets/images/custom-cursor-shape.svg';
$cursor_data_uri = '';
if (file_exists($cursor_svg_path)) {
	$cursor_data_uri = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($cursor_svg_path));
}

$filter_slug = isset($_GET['project_type']) ? sanitize_text_field(wp_unslash($_GET['project_type'])) : '';

if ($is_archive) {
	// Use the main query (archive) so pagination and URL are correct.
	global $wp_query;
	$projects_query = $wp_query;
	$has_projects   = $projects_query->have_posts();
	$paged          = max(1, (int) get_query_var('paged', 1));
	$current_url    = get_post_type_archive_link('project');
	$filter_query_args = $filter_slug ? [ 'project_type' => $filter_slug ] : [];
} else {
	// Paged
	$paged = 1;
	if (isset($_GET['paged']) && is_numeric($_GET['paged'])) {
		$paged = max(1, (int) $_GET['paged']);
	}

	$query_args = [
		'post_type'      => 'project',
		'posts_per_page' => $posts_per_page,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	];

	if ($filter_slug) {
		$query_args['tax_query'] = [
			[
				'taxonomy' => 'project_type',
				'field'    => 'slug',
				'terms'    => $filter_slug,
			],
		];
	}

	$projects_query = new \WP_Query($query_args);
	$has_projects   = $projects_query->have_posts();
	$current_url    = get_permalink();
	$filter_query_args = $filter_slug ? [ 'project_type' => $filter_slug ] : [];
}

$project_types = get_terms([
	'taxonomy'   => 'project_type',
	'hide_empty' => true,
]);
?>

<section class="projects-lister max-w-section mx-auto px-4 md:px-section-x py-section-y overflow-x-clip">
	<!-- Filters bar -->
	<div class="pl-filters flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 pb-8 md:pb-10">
		<div class="flex items-center gap-2 text-brand-black/60 shrink-0">
			<!-- Phosphor Funnel icon (regular weight) -->
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M230.6 49.53A15.81 15.81 0 0 0 216 40H40a16 16 0 0 0-11.76 26.68L96 139.32V216a16 16 0 0 0 24.87 13.32l32-21.34A16 16 0 0 0 160 194.66v-55.34l67.74-72.64a15.79 15.79 0 0 0 2.86-16.49ZM40 56h0Zm106.18 74.8A8 8 0 0 0 144 136v58.66l-32 21.34V136a8 8 0 0 0-2.16-5.47L40 56h176Z"/></svg>
			<span class="font-body text-body-sm font-medium"><?php esc_html_e('Filters', 'boozed'); ?></span>
		</div>
		<nav class="pl-filters__nav flex items-center gap-4 sm:gap-5 overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 min-w-0 flex-nowrap [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden" aria-label="<?php esc_attr_e('Project filters', 'boozed'); ?>">
			<a href="<?php echo esc_url($current_url); ?>" class="font-body text-body-sm font-medium no-underline transition-colors shrink-0 whitespace-nowrap <?php echo !$filter_slug ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
				<?php esc_html_e('Alles', 'boozed'); ?>
			</a>
			<?php if ($project_types && !is_wp_error($project_types)) : ?>
				<?php foreach ($project_types as $term) : ?>
					<a href="<?php echo esc_url(add_query_arg('project_type', $term->slug, $current_url)); ?>" class="font-body text-body-sm font-medium no-underline transition-colors shrink-0 whitespace-nowrap <?php echo $filter_slug === $term->slug ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
						<?php echo esc_html($term->name); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</nav>
	</div>

	<!-- Projects table -->
	<div class="pl-table border-t border-brand-black/10">
		<?php
		if ($has_projects) :
			while ($projects_query->have_posts()) :
				$projects_query->the_post();
				$permalink   = get_permalink();
				$title       = get_the_title();
				$terms       = get_the_terms(get_the_ID(), 'project_type');
				$type_name   = ($terms && !is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';
				$short_desc  = function_exists('get_field') ? (string) get_field('short_description', get_the_ID()) : '';
				if ($short_desc === '' && has_excerpt()) {
					$short_desc = get_the_excerpt();
				}
				$location    = function_exists('get_field') ? (string) get_field('location', get_the_ID()) : '';
				$thumb_id    = get_post_thumbnail_id();
				if (!$thumb_id && function_exists('get_field')) {
					$gallery = get_field('gallery', get_the_ID());
					if (is_array($gallery) && !empty($gallery)) {
						$thumb_id = (int) $gallery[0];
					}
				}
				$img_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
		?>
		<a href="<?php echo esc_url($permalink); ?>"
		   class="pl-table__row relative grid items-center border-b border-brand-black/10 no-underline transition-colors duration-200 group"
		   data-featured-url="<?php echo $img_url ? esc_url($img_url) : ''; ?>">

			<!-- Category -->
			<span class="font-body text-body-sm text-brand-black/50 group-hover:text-brand-white/70 transition-colors"><?php echo esc_html($type_name); ?></span>

			<!-- Title -->
			<h3 class="font-heading font-bold text-h4 md:text-h3 text-brand-black group-hover:text-brand-white transition-colors m-0 pr-4 md:truncate line-clamp-2 md:line-clamp-none"><?php echo esc_html($title); ?></h3>

			<!-- Short description / sub-type -->
			<span class="pl-table__desc font-body text-body-sm text-brand-black/50 group-hover:text-brand-white/70 transition-colors pr-4 md:pr-6"><?php echo esc_html($short_desc); ?></span>

			<!-- Location -->
			<span class="font-body text-body-sm font-medium text-brand-black group-hover:text-brand-white transition-colors md:text-right whitespace-nowrap"><?php echo esc_html($location); ?></span>

			<!-- Featured image (hidden by default, appears on hover) -->
			<?php if ($img_url) : ?>
			<div class="pl-table__image absolute top-1/2 -translate-y-1/2 overflow-hidden opacity-0 scale-95 transition-all duration-300 z-10 shadow-2xl group-hover:opacity-100 group-hover:scale-100">
				<img src="<?php echo esc_url($img_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
			</div>
			<?php endif; ?>
		</a>
		<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>
	</div>

	<?php
	if ($has_projects && $projects_query->max_num_pages > 1) {
		$query = $projects_query;
		$paged = $paged;
		$query_args = $filter_query_args;
		include get_template_directory() . '/resources/views/partials/pagination.php';
	}
	?>

	<!-- Custom cursor (same structure as hero: blob shape + label). Moved to body by JS to avoid parent clipping. -->
	<div id="pl-custom-cursor" class="pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white font-body text-body-xs font-medium leading-tight text-center px-2"><?php echo esc_html($cursor_text); ?></span>
	</div>
</section>

<?php if ($has_projects && empty($GLOBALS['boozed_projects_lister_script_printed'])) : $GLOBALS['boozed_projects_lister_script_printed'] = true;
	$pl_script = <<<'JSEOF'
(function(){
function init(){
if(typeof gsap==="undefined")return;
var table=document.querySelector(".projects-lister .pl-table");
var rows=document.querySelectorAll(".projects-lister .pl-table__row");
var images=document.querySelectorAll(".projects-lister .pl-table__image");
var cursor=document.getElementById("pl-custom-cursor");
if(!table||!cursor)return;
if(cursor.parentNode!==document.body){document.body.appendChild(cursor);}
var cursorActive=false;
var hideCursorTimeout=null;
var HIDE_DELAY_MS=120;
function cancelHideCursor(){if(hideCursorTimeout){clearTimeout(hideCursorTimeout);hideCursorTimeout=null;}}
function showCursor(){
if(!cursor||cursorActive)return;
cursorActive=true;
cursor.style.display="block";
gsap.fromTo(cursor,{scale:0.4,opacity:0},{scale:1,opacity:1,duration:0.3,ease:"back.out(1.7)"});
}
function hideCursor(){if(!cursor)return;cancelHideCursor();cursorActive=false;gsap.to(cursor,{scale:0.4,opacity:0,duration:0.2,ease:"power2.in",onComplete:function(){cursor.style.display="none";}});}
function moveCursor(e){if(!cursor)return;gsap.to(cursor,{x:e.clientX-37,y:e.clientY-37,duration:0.15,ease:"power2.out"});}
table.addEventListener("mouseenter",function(ev){cancelHideCursor();showCursor();moveCursor(ev);});
table.addEventListener("mouseleave",function(){hideCursorTimeout=setTimeout(hideCursor,HIDE_DELAY_MS);});
table.addEventListener("mousemove",moveCursor);
rows.forEach(function(row){row.addEventListener("mouseenter",function(ev){cancelHideCursor();showCursor();moveCursor(ev);});var url=row.getAttribute("data-featured-url");if(url){row.addEventListener("mouseenter",function(){var img=new Image();img.src=url;},{once:true});}});
images.forEach(function(img){img.addEventListener("mouseenter",function(ev){cancelHideCursor();showCursor();moveCursor(ev);});img.addEventListener("mousemove",moveCursor);});
}
if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",init);}else{init();}
})();
JSEOF;
	wp_add_inline_script('gsap', $pl_script, 'after');
?>
<style>
/* Projects lister – row layout */
.pl-table__row {
	display: grid;
	grid-template-columns: 120px 1fr auto auto;
	min-height: 80px;
	padding: 1.25rem 24px;
}
@media (min-width: 768px) {
	.pl-table__row {
		height: 128px;
		padding: 0 70px;
	}
}
/* Row hover */
.pl-table__row:hover {
	background-color: #312783;
}
/* Hide system cursor on whole table area + featured images */
.pl-table,
.pl-table *,
.pl-table__image,
.pl-table__image * {
	cursor: none;
}
/* Description ellipsis */
.pl-table__desc {
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	max-width: 220px;
}
@media (max-width: 767px) {
	.pl-table__desc {
		max-width: none;
	}
}
/* Featured image – positioned at right edge, no border-radius */
.pl-table__image {
	right: -20px;
	width: 240px;
	height: 300px;
}
@media (min-width: 768px) {
	.pl-table__image {
		right: -40px;
		width: 388px;
		height: 484px;
	}
}
/* Mobile: stack columns, touch-friendly spacing */
@media (max-width: 767px) {
	.pl-table__row {
		grid-template-columns: 1fr !important;
		gap: 0.25rem;
		min-height: auto;
		padding: 1rem 0;
	}
	.pl-table__row span:last-of-type {
		text-align: left;
	}
	.pl-table__image {
		display: none;
	}
	.pl-table,
	.pl-table * {
		cursor: pointer !important;
	}
}
</style>
<?php endif; ?>
