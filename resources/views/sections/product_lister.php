<?php

/**
 * Product lister section
 * Grid of products with filters flyout, search, pagination. Reuses product-card. 4th slot = ACF CTA block.
 */

$heading           = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_heading') : '';
$search_url        = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_search_url') : '';
$search_placeholder = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_search_placeholder') : '';
$posts_per_page    = function_exists('get_sub_field') ? max(1, min(48, (int) get_sub_field('product_lister_posts_per_page'))) : 12;
$link_text         = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_link_text') : '';
$quick_filters_raw = function_exists('get_sub_field') ? get_sub_field('product_lister_quick_filters') : null;
$quick_filters     = is_array($quick_filters_raw) ? $quick_filters_raw : [];
$cta_text          = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_cta_text') : '';
$cta_secondary     = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_cta_secondary_text') : '';
$cta_btn1_label    = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_cta_btn1_label') : '';
$cta_btn1_url      = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_cta_btn1_url') : '';
$cta_btn2_label    = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_cta_btn2_label') : '';
$cta_btn2_url      = function_exists('get_sub_field') ? (string) get_sub_field('product_lister_cta_btn2_url') : '';

$heading            = $heading ?: __('Verhuur', 'boozed');
$search_placeholder = $search_placeholder ?: __('Online catalogus (meer dan 2000 items)', 'boozed');
if ($search_url === '') {
	$search_url = function_exists('boozed_plp_url') ? boozed_plp_url() : get_permalink();
}

$show_cta_block = !is_user_logged_in() && ($cta_text !== '' || $cta_secondary !== '' || ($cta_btn1_label !== '' && $cta_btn1_url !== '') || $cta_btn2_label !== '');

$current_url = get_permalink();
$assortiment_url = function_exists('boozed_plp_url') ? boozed_plp_url() : home_url('/assortiment');
$acf_login_url = function_exists('get_field') ? (string) get_field('pdp_login_url', 'option') : '';
if ($acf_login_url === '') {
	$acf_login_url = function_exists('boozed_login_page_url') ? boozed_login_page_url() : home_url('/login');
}
$acf_login_url = wp_validate_redirect(
	$acf_login_url,
	function_exists('boozed_login_page_url') ? boozed_login_page_url() : home_url('/login')
);
$cta_login_href = add_query_arg('redirect_to', $assortiment_url, $acf_login_url);
$product_cat_raw = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';
$product_cat_slugs = [];
if (is_array($product_cat_raw)) {
	$product_cat_slugs = array_filter(array_map('sanitize_text_field', $product_cat_raw));
} elseif (is_string($product_cat_raw) && $product_cat_raw !== '') {
	$product_cat_slugs = array_filter(array_map('trim', explode(',', sanitize_text_field(wp_unslash($product_cat_raw)))));
}
$product_tag_raw = isset($_GET['product_tag']) ? $_GET['product_tag'] : '';
$product_tag_slugs = [];
if (is_array($product_tag_raw)) {
	$product_tag_slugs = array_filter(array_map('sanitize_text_field', $product_tag_raw));
} elseif (is_string($product_tag_raw) && $product_tag_raw !== '') {
	$product_tag_slugs = array_filter(array_map('trim', explode(',', $product_tag_raw)));
}
$search_query_raw = '';
if (isset($_GET['q'])) {
	$search_query_raw = wp_unslash($_GET['q']);
} elseif (isset($_GET['s'])) {
	// Backward compatibility for old links that still use ?s=...
	$search_query_raw = wp_unslash($_GET['s']);
}
$search_query = $search_query_raw !== '' ? sanitize_text_field($search_query_raw) : '';
// Support both pagination URL styles:
// - `/page/N/` => WordPress sets the `paged` query var
// - `...?paged=N` => `paged` appears in the query string
$paged_from_wp = get_query_var('paged', 1);
$paged = is_numeric($paged_from_wp) ? max(1, (int) $paged_from_wp) : 1;
if (isset($_GET['paged']) && is_numeric($_GET['paged'])) {
	$paged = max(1, (int) $_GET['paged']);
}

$query_per_page = $show_cta_block ? ($posts_per_page - 1) : $posts_per_page;
$query_per_page = max(1, $query_per_page);

$query_args = [
	'post_type'      => 'product',
	'posts_per_page' => $query_per_page,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
];

if ($search_query !== '') {
	$query_args['s'] = $search_query;
}

$tax_queries = [];
if (! empty($product_cat_slugs) && taxonomy_exists('product_cat')) {
	$tax_queries[] = [
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => $product_cat_slugs,
	];
}
if (! empty($product_tag_slugs) && taxonomy_exists('product_tag')) {
	$tax_queries[] = [
		'taxonomy' => 'product_tag',
		'field'    => 'slug',
		'terms'    => $product_tag_slugs,
	];
}
if (! empty($tax_queries)) {
	$query_args['tax_query'] = $tax_queries;
}

$products_query = new \WP_Query($query_args);
$items = [];
if ($products_query->have_posts()) {
	$show_prices = is_user_logged_in() && function_exists('wc_get_product');
	while ($products_query->have_posts()) {
		$products_query->the_post();
		$pid = get_the_ID();
		$thumb_id = get_post_thumbnail_id($pid);
		$img_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
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
	wp_reset_postdata();
}

$has_items = ! empty($items) || $show_cta_block;
$filter_query_args = array_filter([
	'q' => $search_query !== '' ? $search_query : null,
]);
if (! empty($product_cat_slugs)) {
	$filter_query_args['product_cat'] = $product_cat_slugs;
}
if (! empty($product_tag_slugs)) {
	$filter_query_args['product_tag'] = $product_tag_slugs;
}

$product_cats = taxonomy_exists('product_cat') ? get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0]) : [];
$product_tags = taxonomy_exists('product_tag') ? get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]) : [];

$section_id = 'product-lister-' . (function_exists('get_row_index') ? get_row_index() : uniqid());
$flyout_id = $section_id . '-filters';
?>

<section class="product-lister max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y" id="<?php echo esc_attr($section_id); ?>">
	<!-- Header: title left, filter tags + filter button + search right-aligned -->
	<div class="product-lister__header flex flex-col gap-4 mb-6 md:mb-8">
		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
			<h2 class="product-lister__heading font-heading font-bold text-h2 md:text-h2-lg text-brand-indigo m-0 shrink-0"><?php echo esc_html($heading); ?></h2>
			<div class="flex flex-wrap items-center justify-end gap-2 sm:gap-3">
			<?php if (! empty($product_cat_slugs) || ! empty($product_tag_slugs)) : ?>
				<?php
				foreach ($product_cat_slugs as $cat_slug) {
					$cat_term = get_term_by('slug', $cat_slug, 'product_cat');
					$remaining_cats = array_values(array_diff($product_cat_slugs, [$cat_slug]));
					$clear_url = remove_query_arg('product_cat', $current_url);
					foreach ($remaining_cats as $rc) {
						$clear_url = add_query_arg('product_cat[]', $rc, $clear_url);
					}
					if ($paged > 1) {
						$clear_url = add_query_arg('paged', 1, $clear_url);
					}
				?>
				<span class="product-lister__filter-tag inline-flex items-center gap-1 pl-2 pr-1 py-1 rounded bg-brand-border font-body text-body-sm text-brand-black">
					<?php echo esc_html($cat_term ? $cat_term->name : $cat_slug); ?>
					<a href="<?php echo esc_url($clear_url); ?>" class="product-lister__filter-remove inline-flex p-1 rounded hover:bg-brand-black/10" data-remove-filter="product_cat" data-remove-slug="<?php echo esc_attr($cat_slug); ?>" data-no-transition aria-label="<?php esc_attr_e('Remove filter', 'boozed'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M205.66 194.34a8 8 0 0 1-11.32 11.32L128 139.31 61.66 205.66a8 8 0 0 1-11.32-11.32L116.69 128 50.34 61.66a8 8 0 0 1 11.32-11.32L128 116.69l66.34-66.35a8 8 0 0 1 11.32 11.32L139.31 128Z"/></svg>
					</a>
				</span>
				<?php }
					foreach ($product_tag_slugs as $tag_slug) {
						$tag_term = get_term_by('slug', $tag_slug, 'product_tag');
						$remaining = array_values(array_diff($product_tag_slugs, [$tag_slug]));
						$clear_url = remove_query_arg('product_tag', $current_url);
						foreach ($remaining as $t) {
							$clear_url = add_query_arg('product_tag[]', $t, $clear_url);
						}
						if ($paged > 1) {
							$clear_url = add_query_arg('paged', 1, $clear_url);
						}
					?>
					<span class="product-lister__filter-tag inline-flex items-center gap-1 pl-2 pr-1 py-1 rounded bg-brand-border font-body text-body-sm text-brand-black">
						<?php echo esc_html($tag_term ? $tag_term->name : $tag_slug); ?>
						<a href="<?php echo esc_url($clear_url); ?>" class="product-lister__filter-remove inline-flex p-1 rounded hover:bg-brand-black/10" data-remove-filter="product_tag" data-remove-slug="<?php echo esc_attr($tag_slug); ?>" data-no-transition aria-label="<?php esc_attr_e('Remove filter', 'boozed'); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M205.66 194.34a8 8 0 0 1-11.32 11.32L128 139.31 61.66 205.66a8 8 0 0 1-11.32-11.32L116.69 128 50.34 61.66a8 8 0 0 1 11.32-11.32L128 116.69l66.34-66.35a8 8 0 0 1 11.32 11.32L139.31 128Z"/></svg>
						</a>
					</span>
					<?php } ?>
				<?php endif; ?>
				<button type="button" class="product-lister__filters-trigger inline-flex items-center gap-2 font-body text-body-sm font-medium text-brand-black hover:text-brand-indigo focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2 rounded px-2 py-1 shrink-0" aria-expanded="false" aria-controls="<?php echo esc_attr($flyout_id); ?>" data-product-lister-trigger>
					<svg width="20" height="20" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12.5348 21.3488C12.2532 21.3488 12.0173 21.2545 11.8269 21.0659C11.6365 20.8775 11.5414 20.6419 11.5414 20.3592C11.5414 20.0767 11.6368 19.8397 11.8278 19.6484C12.0187 19.4573 12.2544 19.3617 12.5348 19.3617H15.4724C15.7518 19.3617 15.9862 19.459 16.1754 19.6537C16.3644 19.8483 16.4588 20.087 16.4588 20.3697C16.4588 20.6522 16.3634 20.8861 16.1724 21.0712C15.9815 21.2563 15.7458 21.3488 15.4654 21.3488H12.5348ZM7.83339 14.9867C7.55125 14.9867 7.31627 14.8924 7.12843 14.7038C6.9406 14.5154 6.84668 14.2796 6.84668 13.9965C6.84668 13.7136 6.9406 13.4778 7.12843 13.2892C7.31627 13.1006 7.55125 13.0063 7.83339 13.0063H20.1601C20.4415 13.0063 20.6774 13.1023 20.868 13.2945C21.0583 13.4864 21.1535 13.7238 21.1535 14.0067C21.1535 14.2898 21.0583 14.5239 20.868 14.709C20.6774 14.8942 20.4415 14.9867 20.1601 14.9867H7.83339ZM4.31239 8.63129C4.03025 8.63129 3.79527 8.53572 3.60743 8.34458C3.4196 8.15344 3.32568 7.91651 3.32568 7.63379C3.32568 7.35126 3.42116 7.11569 3.6121 6.92708C3.80304 6.73847 4.03881 6.64417 4.31939 6.64417H23.6878C23.9694 6.64417 24.2053 6.74022 24.3957 6.93233C24.5862 7.12425 24.6815 7.36147 24.6815 7.644C24.6815 7.92672 24.5847 8.162 24.391 8.34983C24.1975 8.53747 23.9608 8.63129 23.6808 8.63129H4.31239Z" fill="currentColor"/></svg>
					<?php esc_html_e('Filters', 'boozed'); ?>
				</button>
				<form class="product-lister__search flex shrink-0 min-w-0" action="<?php echo esc_url($search_url); ?>" method="get" role="search" aria-label="<?php esc_attr_e('Search catalog', 'boozed'); ?>">
					<?php foreach ($product_cat_slugs as $c) : ?><input type="hidden" name="product_cat[]" value="<?php echo esc_attr($c); ?>"><?php endforeach; ?>
					<?php foreach ($product_tag_slugs as $t) : ?><input type="hidden" name="product_tag[]" value="<?php echo esc_attr($t); ?>"><?php endforeach; ?>
					<label for="<?php echo esc_attr($section_id); ?>-search" class="sr-only"><?php esc_attr_e('Search', 'boozed'); ?></label>
					<span class="product-lister__search-icon absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-brand-black/50" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" fill="currentColor"><path d="M229.66 218.34l-50.07-50.06a88.11 88.11 0 1 0-11.32 11.32l50.07 50.06a8 8 0 0 0 11.32-11.32zM40 112a72 72 0 1 1 72 72 72.08 72.08 0 0 1-72-72z"/></svg>
					</span>
					<input type="search" id="<?php echo esc_attr($section_id); ?>-search" name="q" class="product-lister__search-input w-full min-w-[200px] sm:w-[300px] md:w-[500px] h-12 pl-10 pr-4 border border-brand-black/15 rounded-md font-body text-body-sm text-brand-black placeholder:text-brand-black/50 focus:outline-none focus:ring-2 focus:ring-brand-indigo/30 focus:border-brand-indigo" placeholder="<?php echo esc_attr($search_placeholder); ?>" value="<?php echo esc_attr($search_query); ?>">
				</form>
			</div>
		</div>
	</div>

	<?php if (! empty($quick_filters)) : ?>
	<div class="product-lister__quick-filters flex flex-wrap gap-2 mb-6 md:mb-8" data-product-lister-quick-filters>
		<?php $all_active = empty($product_cat_slugs); ?>
		<button type="button" data-quick-filter-slug="" class="product-lister__quick-chip inline-flex items-center px-4 py-2 rounded-full font-body text-body-sm font-medium border transition-colors cursor-pointer<?php echo $all_active ? ' product-lister__quick-chip--active' : ''; ?>">
			<?php esc_html_e('Alles', 'boozed'); ?>
		</button>
		<?php foreach ($quick_filters as $qf_term) :
			if (! $qf_term instanceof \WP_Term) continue;
			$is_active = in_array($qf_term->slug, $product_cat_slugs, true);
		?>
		<button type="button" data-quick-filter-slug="<?php echo esc_attr($qf_term->slug); ?>" class="product-lister__quick-chip inline-flex items-center px-4 py-2 rounded-full font-body text-body-sm font-medium border transition-colors cursor-pointer<?php echo $is_active ? ' product-lister__quick-chip--active' : ''; ?>">
			<?php echo esc_html($qf_term->name); ?>
		</button>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<!-- Filters flyout -->
	<div id="<?php echo esc_attr($flyout_id); ?>" class="product-lister__flyout fixed inset-0 left-0 z-[100] w-full max-w-md bg-brand-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-out flex flex-col h-screen" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($flyout_id); ?>-title" data-product-lister-flyout>
		<!-- Fixed header -->
		<div class="product-lister__flyout-header flex-shrink-0 px-6 md:px-8 pt-6 md:pt-8">
			<div class="flex items-center justify-between mb-4">
				<h3 id="<?php echo esc_attr($flyout_id); ?>-title" class="font-heading font-bold text-h4 text-brand-black m-0"><?php esc_html_e('Producten filters', 'boozed'); ?></h3>
				<button type="button" class="product-lister__flyout-close flex h-10 w-10 items-center justify-center rounded-full text-brand-black hover:bg-brand-border focus:outline-none focus:ring-2 focus:ring-brand-purple" aria-label="<?php esc_attr_e('Close filters', 'boozed'); ?>" data-product-lister-close>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256" fill="currentColor"><path d="M205.66 194.34a8 8 0 0 1-11.32 11.32L128 139.31 61.66 205.66a8 8 0 0 1-11.32-11.32L116.69 128 50.34 61.66a8 8 0 0 1 11.32-11.32L128 116.69l66.34-66.35a8 8 0 0 1 11.32 11.32L139.31 128Z"/></svg>
				</button>
			</div>
			<div class="h-0.5 bg-brand-indigo rounded-full"></div>
		</div>

		<!-- Form wraps scrollable content + fixed footer -->
		<form action="<?php echo esc_url($current_url); ?>" method="get" class="product-lister__flyout-form flex flex-col flex-1 min-h-0">
			<?php if ($paged > 1) : ?>
				<input type="hidden" name="paged" value="1">
			<?php endif; ?>

			<!-- Wrapper gives scroll area a definite height (flex-1 min-h-0 + relative for absolute child) -->
			<div class="product-lister__flyout-scroll-wrap flex-1 min-h-0 relative">
			<!-- Scrollable content: absolute fill so it has a definite height and can scroll -->
			<div class="product-lister__flyout-scroll absolute inset-0 overflow-y-auto overflow-x-hidden px-6 md:px-8 py-6" data-lenis-prevent>
				<!-- Search -->
				<div class="mb-6">
					<label for="<?php echo esc_attr($flyout_id); ?>-flyout-search" class="block font-body text-body-sm font-medium text-brand-black mb-2"><?php esc_html_e('Direct zoeken', 'boozed'); ?></label>
					<div class="relative">
						<span class="absolute left-3 top-1/2 -translate-y-1/2 text-brand-black/50" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 256 256" fill="currentColor"><path d="M229.66 218.34l-50.07-50.06a88.11 88.11 0 1 0-11.32 11.32l50.07 50.06a8 8 0 0 0 11.32-11.32zM40 112a72 72 0 1 1 72 72 72.08 72.08 0 0 1-72-72z"/></svg>
						</span>
						<input type="search" id="<?php echo esc_attr($flyout_id); ?>-flyout-search" name="q" class="w-full h-11 pl-10 pr-4 border border-brand-black/15 rounded-md font-body text-body-sm text-brand-black placeholder:text-brand-black/50" placeholder="<?php esc_attr_e('Meer dan 2000 items', 'boozed'); ?>" value="<?php echo esc_attr($search_query); ?>">
					</div>
				</div>

				<hr class="border-brand-border mb-6 mt-0">

			<!-- Categories -->
			<div class="mb-6">
				<span class="block font-body text-body-sm font-medium text-brand-black mb-3"><?php esc_html_e('Filter op categorie', 'boozed'); ?></span>
				<div class="space-y-2">
					<label class="flex items-center gap-2 font-body text-body-sm text-brand-black cursor-pointer">
						<input type="checkbox" class="product-lister__cat-all appearance-none border border-brand-black/30 text-brand-indigo focus:ring-brand-indigo product-lister__cat-radio" <?php echo empty($product_cat_slugs) ? ' checked' : ''; ?>>
						<?php esc_html_e('Alle producten', 'boozed'); ?>
					</label>
					<?php if ($product_cats && ! is_wp_error($product_cats)) : ?>
						<?php foreach ($product_cats as $term) : ?>
							<label class="flex items-center gap-2 font-body text-body-sm text-brand-black cursor-pointer">
								<input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($term->slug); ?>" <?php echo in_array($term->slug, $product_cat_slugs, true) ? ' checked' : ''; ?> class="product-lister__cat-check appearance-none border border-brand-black/30 text-brand-indigo focus:ring-brand-indigo product-lister__cat-radio">
								<?php echo esc_html($term->name); ?>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>

				<hr class="border-brand-border mb-6 mt-0">

				<!-- Styles -->
				<div>
				<span class="block font-body text-body-sm font-medium text-brand-black mb-3"><?php esc_html_e('Filter op stijl', 'boozed'); ?></span>
					<div class="flex flex-wrap gap-2">
						<?php if ($product_tags && ! is_wp_error($product_tags)) : ?>
							<?php foreach ($product_tags as $term) : ?>
								<label class="inline-flex items-center cursor-pointer">
									<input type="checkbox" name="product_tag[]" value="<?php echo esc_attr($term->slug); ?>" <?php echo in_array($term->slug, $product_tag_slugs, true) ? ' checked' : ''; ?> class="sr-only product-lister__style-check">
									<span class="inline-flex px-3 py-1.5 rounded-sm font-body text-body-sm border border-brand-black/20 text-brand-black hover:border-brand-indigo/50 hover:bg-brand-nude/50 <?php echo in_array($term->slug, $product_tag_slugs, true) ? ' bg-brand-indigo border-brand-indigo text-white' : ''; ?>"><?php echo esc_html($term->name); ?> (<?php echo esc_html($term->count); ?>)</span>
								</label>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			</div>

			<!-- Fixed footer: always visible -->
			<div class="product-lister__flyout-footer flex-shrink-0 border-t border-brand-border px-6 md:px-8 py-4 flex items-center justify-between gap-3">
				<a href="<?php echo esc_url($current_url); ?>" class="product-lister__flyout-clear font-body text-body-sm font-medium text-brand-black hover:text-brand-indigo"><?php esc_html_e('Verwijder filters', 'boozed'); ?></a>
				<button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 font-body text-body-sm font-medium bg-brand-coral text-brand-white hover:opacity-90 rounded focus:outline-none focus:ring-2 focus:ring-brand-coral focus:ring-offset-2"><?php esc_html_e('Toepassen', 'boozed'); ?></button>
			</div>
		</form>
	</div>
	<div class="product-lister__flyout-backdrop fixed inset-0 z-[99] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300" aria-hidden="true" data-product-lister-backdrop></div>

	<div class="product-lister__results" data-product-lister-results>
	<?php if ($has_items) : ?>
		<div class="product-lister__grid grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" data-product-lister-grid>
			<?php
			$cell_index = 0;
			$item_index = 0;
			$total_cells = count($items) + ($show_cta_block ? 1 : 0);
			while ($cell_index < $total_cells) :
				if ($show_cta_block && ($cell_index === 3 || (count($items) === 0 && $cell_index === 0))) :
					?>
					<div class="product-lister__cta-slot flex flex-col aspect-square md:aspect-[314/435] w-full rounded overflow-hidden bg-brand-indigo text-brand-white p-5 md:p-6 md:pr-20 min-w-0 justify-between">
						<div class="flex flex-col gap-4">
							<?php if ($cta_text !== '') : ?>
								<h5 class="product-lister__cta-heading font-heading font-bold text-h5 md:text-h5-lg text-brand-white m-0"><?php echo esc_html($cta_text); ?></h5>
							<?php endif; ?>
							<?php if ($cta_secondary !== '') : ?>
								<p class="product-lister__cta-text font-body text-body text-brand-white/90 m-0"><?php echo esc_html($cta_secondary); ?></p>
							<?php endif; ?>
						</div>
						<div class="product-lister__cta-buttons flex flex-col items-start gap-3 mt-6 md:mt-8">
							<?php if ($cta_btn1_label !== '' && $cta_btn1_url !== '') : ?>
								<?php \App\Components::render('button', ['variant' => 'coral', 'label' => $cta_btn1_label, 'href' => $cta_btn1_url, 'class' => '!bg-brand-coral self-start']); ?>
							<?php endif; ?>
							<?php if ($cta_btn2_label !== '') : ?>
								<?php
								$cta_btn2_href = $cta_login_href;
								if ($cta_btn2_href !== '') :
								?>
									<a href="<?php echo esc_url($cta_btn2_href); ?>" class="product-lister__cta-secondary self-start font-body text-body-md font-medium text-brand-white no-underline relative inline-block hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-brand-white focus:ring-offset-2 focus:ring-offset-brand-indigo"><?php echo esc_html($cta_btn2_label); ?></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
					<?php
					$cell_index++;
					continue;
				endif;
				if ($item_index < count($items)) :
					$item = $items[$item_index];
					?>
					<div class="product-lister__card">
						<?php \App\Components::render('product-card', $item); ?>
					</div>
					<?php
					$item_index++;
				endif;
				$cell_index++;
			endwhile;
			?>
		</div>

		<?php
		if ($products_query->max_num_pages > 1) {
			$query      = $products_query;
			$query_args = $filter_query_args;
			include get_template_directory() . '/resources/views/partials/pagination.php';
		}
		?>
	<?php else : ?>
		<p class="font-body text-body text-brand-black/60"><?php esc_html_e('Geen producten gevonden.', 'boozed'); ?></p>
	<?php endif; ?>
	</div>
</section>

<style>
.product-lister__search { position: relative; }
.product-lister__flyout.is-open { transform: translateX(0); }
.product-lister__flyout-backdrop.is-open { opacity: 1; pointer-events: auto; }

/* Lock body scroll when flyout is open (do not use touch-action: none – it blocks scrolling inside the flyout) */
body.product-lister-flyout-open {
	overflow: hidden;
	position: fixed;
	width: 100%;
}

/* Flyout: explicit height so inner flex/absolute layout can resolve */
.product-lister__flyout {
	height: 100vh;
	height: 100dvh;
}

/* Scroll area: absolute fill = definite height, so overflow-y: auto can scroll */
.product-lister__flyout-scroll {
	overscroll-behavior: contain;
	-webkit-overflow-scrolling: touch;
	touch-action: pan-y;
}

/* Category checkboxes: circular (radio-style) */
.product-lister__cat-radio {
	-webkit-appearance: none;
	appearance: none;
	width: 1.25rem;
	height: 1.25rem;
	border-radius: 50%;
	flex-shrink: 0;
}
.product-lister__cat-radio:checked {
	background-color: #312783;
	border-color: #312783;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
	background-repeat: no-repeat;
	background-position: center;
}

/* Quick-select filter chips */
.product-lister__quick-chip {
	background: #fff;
	border-color: rgba(0,0,0,.2);
	color: var(--color-brand-black, #1a1a2e);
}
.product-lister__quick-chip:hover {
	border-color: rgba(49,39,131,.5);
	background: var(--color-brand-nude, #f5f0eb);
}
.product-lister__quick-chip--active,
.product-lister__quick-chip--active:hover {
	background: #312783;
	border-color: #312783;
	color: #fff;
}

/* Results fade for AJAX loading */
.product-lister__results { transition: opacity .2s ease; }
.product-lister__results.is-loading { opacity: .4; pointer-events: none; }

/* Style filter chips: when checkbox is checked, style the span (indigo bg, white text) */
.product-lister__style-check:checked + span { background-color: #312783; border-color: #312783; color: #fff; }

/* Product cards: keep PLP tiles compact and consistent */
.product-lister__card { min-width: 0; }
@media (min-width: 768px) {
	.product-lister__card {
		width: 100%;
	}
	.product-lister__card .product-card {
		height: 100%;
		display: flex;
		flex-direction: column;
		min-height: 0;
	}
	.product-lister__card .product-card__img {
		aspect-ratio: 1 / 1;
		flex: 0 0 auto;
		min-height: 0;
	}
	.product-lister__card .product-card__img img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
}
</style>

<script>
(function() {
	function init() {
		var section = document.getElementById('<?php echo esc_js($section_id); ?>');
		if (!section) return;
		var trigger = section.querySelector('[data-product-lister-trigger]');
		var flyout = section.querySelector('[data-product-lister-flyout]');
		var closeBtn = section.querySelector('[data-product-lister-close]');
		var backdrop = section.querySelector('[data-product-lister-backdrop]');
		if (!trigger || !flyout) return;

		var savedScrollY = 0;

		function open() {
			savedScrollY = window.scrollY;
			flyout.classList.add('is-open');
			if (backdrop) backdrop.classList.add('is-open');
			document.body.classList.add('product-lister-flyout-open');
			document.body.style.top = '-' + savedScrollY + 'px';
			trigger.setAttribute('aria-expanded', 'true');
		}
		function close() {
			flyout.classList.remove('is-open');
			if (backdrop) backdrop.classList.remove('is-open');
			document.body.classList.remove('product-lister-flyout-open');
			document.body.style.top = '';
			window.scrollTo(0, savedScrollY);
			trigger.setAttribute('aria-expanded', 'false');
		}

		trigger.addEventListener('click', function() {
			if (flyout.classList.contains('is-open')) close(); else open();
		});
		if (closeBtn) closeBtn.addEventListener('click', close);
		if (backdrop) backdrop.addEventListener('click', close);
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && flyout.classList.contains('is-open')) close();
		});

		var catAll = flyout.querySelector('.product-lister__cat-all');
		var catChecks = flyout.querySelectorAll('.product-lister__cat-check');
		if (catAll && catChecks.length) {
			catAll.addEventListener('change', function() {
				if (this.checked) {
					catChecks.forEach(function(cb) { cb.checked = false; });
				}
			});
			catChecks.forEach(function(cb) {
				cb.addEventListener('change', function() {
					var anyChecked = Array.prototype.some.call(catChecks, function(c) { return c.checked; });
					catAll.checked = !anyChecked;
				});
			});
		}

		/* ── Shared inline-fetch logic (no page transition) ── */
		var activeCats = <?php echo json_encode(array_values($product_cat_slugs)); ?>;
		var activeTags = <?php echo json_encode(array_values($product_tag_slugs)); ?>;
		var fetching = false;
		var quickFiltersWrap = section.querySelector('[data-product-lister-quick-filters]');

		function buildFilterUrl() {
			var url = new URL(<?php echo json_encode($current_url); ?>);
			url.searchParams.delete('product_cat[]');
			url.searchParams.delete('product_cat');
			url.searchParams.delete('product_tag[]');
			url.searchParams.delete('product_tag');
			url.searchParams.delete('paged');
			activeCats.forEach(function(c) { url.searchParams.append('product_cat[]', c); });
			activeTags.forEach(function(t) { url.searchParams.append('product_tag[]', t); });
			return url.toString();
		}

		function updateChipStates() {
			if (!quickFiltersWrap) return;
			quickFiltersWrap.querySelectorAll('[data-quick-filter-slug]').forEach(function(btn) {
				var slug = btn.getAttribute('data-quick-filter-slug');
				var isActive = slug === '' ? activeCats.length === 0 : activeCats.indexOf(slug) !== -1;
				btn.classList.toggle('product-lister__quick-chip--active', isActive);
			});
		}

		function fetchFilteredResults() {
			if (fetching) return;
			fetching = true;
			var targetUrl = buildFilterUrl();
			var results = section.querySelector('[data-product-lister-results]');
			if (results) results.classList.add('is-loading');

			history.replaceState(null, '', targetUrl);

			fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
				.then(function(r) { return r.text(); })
				.then(function(html) {
					var parser = new DOMParser();
					var doc = parser.parseFromString(html, 'text/html');
					var newSection = doc.getElementById('<?php echo esc_js($section_id); ?>');
					if (!newSection) { window.location.href = targetUrl; return; }

					var newResults = newSection.querySelector('[data-product-lister-results]');
					if (newResults && results) {
						results.innerHTML = newResults.innerHTML;
						results.classList.remove('is-loading');
					}

					var newTags = newSection.querySelectorAll('.product-lister__filter-tag');
					var oldTagsParent = section.querySelector('.product-lister__header .flex.flex-wrap');
					if (oldTagsParent) {
						oldTagsParent.querySelectorAll('.product-lister__filter-tag').forEach(function(t) { t.remove(); });
						var insertBefore = oldTagsParent.querySelector('.product-lister__filters-trigger');
						newTags.forEach(function(tag) {
							oldTagsParent.insertBefore(tag, insertBefore);
						});
						bindFilterTagRemoval();
					}
				})
				.catch(function() {
					window.location.href = targetUrl;
				})
				.finally(function() {
					fetching = false;
				});
		}

		/* ── Filter tag removal (× buttons on active filter chips) ── */
		function bindFilterTagRemoval() {
			section.querySelectorAll('.product-lister__filter-remove').forEach(function(link) {
				link.addEventListener('click', function(e) {
					e.preventDefault();
					var taxonomy = this.getAttribute('data-remove-filter');
					var slug = this.getAttribute('data-remove-slug');
					if (taxonomy === 'product_cat') {
						activeCats = activeCats.filter(function(c) { return c !== slug; });
					} else if (taxonomy === 'product_tag') {
						activeTags = activeTags.filter(function(t) { return t !== slug; });
					}
					updateChipStates();
					fetchFilteredResults();
				});
			});
		}
		bindFilterTagRemoval();

		/* ── Quick-filter chips ── */
		if (quickFiltersWrap) {
			quickFiltersWrap.addEventListener('click', function(e) {
				var btn = e.target.closest('[data-quick-filter-slug]');
				if (!btn) return;
				var slug = btn.getAttribute('data-quick-filter-slug');
				if (slug === '') {
					activeCats = [];
				} else {
					var idx = activeCats.indexOf(slug);
					if (idx !== -1) {
						activeCats.splice(idx, 1);
					} else {
						activeCats.push(slug);
					}
				}
				updateChipStates();
				fetchFilteredResults();
			});
		}
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
