<?php
/**
 * PDP view – single product layout (design system).
 * Expects global $product (WC_Product) and uses get_the_ID().
 */
$post_id = get_the_ID();
if (!$product || !is_a($product, 'WC_Product')) {
    return;
}

$shop_url = function_exists('boozed_plp_url') ? boozed_plp_url() : home_url('/');
$product_cats = taxonomy_exists('product_cat') ? get_the_terms($post_id, 'product_cat') : [];
$product_tags = taxonomy_exists('product_tag') ? get_the_terms($post_id, 'product_tag') : [];
if (is_wp_error($product_cats)) {
    $product_cats = [];
}
if (is_wp_error($product_tags)) {
    $product_tags = [];
}
$first_cat = is_array($product_cats) && !empty($product_cats) ? $product_cats[0] : null;

$length = $product->get_length();
$width  = $product->get_width();
$height = $product->get_height();
$has_dimensions = ($length !== '' && (float) $length > 0) || ($width !== '' && (float) $width > 0) || ($height !== '' && (float) $height > 0);
$dimensions_long  = '';
if ($has_dimensions) {
    $l = $length !== '' ? (float) $length : 0;
    $b = $width !== '' ? (float) $width : 0;
    $h = $height !== '' ? (float) $height : 0;
    $dimensions_long  = sprintf(
        __('Afmetingen: lengte %s cm, breedte %s cm, hoogte %s cm.', 'boozed'),
        round($l),
        round($b),
        round($h)
    );
}

$account_url = (function_exists('get_field') ? get_field('pdp_registration_url', 'option') : null) ?: home_url('/registreren');
$login_url = (function_exists('get_field') ? get_field('pdp_login_url', 'option') : null) ?: home_url('/login');
$maatwerk_url = (function_exists('get_field') ? get_field('pdp_maatwerk_url', 'option') : null) ?: '#';

// Translatable texts from global ACF (PDP)
$pdp_breadcrumb_verhuur   = (function_exists('get_field') ? (string) get_field('pdp_breadcrumb_verhuur', 'option') : '') ?: __('Verhuur', 'boozed');
$pdp_tab_beschrijving     = (function_exists('get_field') ? (string) get_field('pdp_tab_beschrijving', 'option') : '') ?: __('Beschrijving', 'boozed');
$pdp_tab_extra            = (function_exists('get_field') ? (string) get_field('pdp_tab_extra', 'option') : '') ?: __('Extra informatie', 'boozed');
$pdp_geen_beschrijving    = (function_exists('get_field') ? (string) get_field('pdp_geen_beschrijving', 'option') : '') ?: __('Geen beschrijving.', 'boozed');
$pdp_geen_extra_info      = (function_exists('get_field') ? (string) get_field('pdp_geen_extra_info', 'option') : '') ?: __('Geen extra informatie beschikbaar.', 'boozed');
$pdp_meer_over_product    = (function_exists('get_field') ? (string) get_field('pdp_meer_over_product', 'option') : '') ?: __('Meer over dit product', 'boozed');
$pdp_gerelateerde         = (function_exists('get_field') ? (string) get_field('pdp_gerelateerde', 'option') : '') ?: __('Gerelateerde producten', 'boozed');
$pdp_benieuwd_prijs       = (function_exists('get_field') ? (string) get_field('pdp_benieuwd_prijs', 'option') : '') ?: __('Benieuwd naar de prijs?', 'boozed');
$pdp_cta_account          = (function_exists('get_field') ? (string) get_field('pdp_cta_account', 'option') : '') ?: __('Maak een account aan', 'boozed');
$pdp_cta_account          = trim(preg_replace('/\s*>\s*$/', '', $pdp_cta_account));
$pdp_cta_login            = (function_exists('get_field') ? (string) get_field('pdp_cta_login', 'option') : '') ?: __('Inloggen', 'boozed');
$pdp_usp_aria             = (function_exists('get_field') ? (string) get_field('pdp_usp_aria', 'option') : '') ?: __('Voordelen', 'boozed');
$pdp_usp_1                = (function_exists('get_field') ? (string) get_field('pdp_usp_1', 'option') : '') ?: __('Alles voor je event onder één dak', 'boozed');
$pdp_usp_2                = (function_exists('get_field') ? (string) get_field('pdp_usp_2', 'option') : '') ?: __('Duurzaam & impact-gedreven', 'boozed');
$pdp_usp_3                = (function_exists('get_field') ? (string) get_field('pdp_usp_3', 'option') : '') ?: __('Het grootste assortiment van Nederland', 'boozed');
$pdp_product_info_heading = (function_exists('get_field') ? (string) get_field('pdp_product_info_heading', 'option') : '') ?: __('Product informatie', 'boozed');
$pdp_categorieen         = (function_exists('get_field') ? (string) get_field('pdp_categorieen', 'option') : '') ?: __('Categorieën', 'boozed');
$pdp_tags                 = (function_exists('get_field') ? (string) get_field('pdp_tags', 'option') : '') ?: __('Tags', 'boozed');
$pdp_maatwerk_image       = function_exists('get_field') ? get_field('pdp_maatwerk_image', 'option') : null;
$pdp_maatwerk_heading     = (function_exists('get_field') ? (string) get_field('pdp_maatwerk_heading', 'option') : '') ?: __('Kun je niet vinden wat je zoekt in ons verhuurassortiment?', 'boozed');
$pdp_maatwerk_text        = (function_exists('get_field') ? (string) get_field('pdp_maatwerk_text', 'option') : '') ?: __('Geen zorgen. We maken het op maat, passen het aan of halen het voor je op de juiste plek.', 'boozed');
$pdp_maatwerk_btn         = (function_exists('get_field') ? (string) get_field('pdp_maatwerk_btn', 'option') : '') ?: __('Meer over maatwerk', 'boozed');
$pdp_related_link_text    = (function_exists('get_field') ? (string) get_field('pdp_related_link_text', 'option') : '') ?: __('Bekijken >', 'boozed');
$pdp_label_afmetingen     = (function_exists('get_field') ? (string) get_field('pdp_label_afmetingen', 'option') : '') ?: __('Afmetingen', 'boozed');
$pdp_label_gewicht        = (function_exists('get_field') ? (string) get_field('pdp_label_gewicht', 'option') : '') ?: __('Gewicht', 'boozed');

$thumb_id = get_post_thumbnail_id($post_id);
$main_image_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
$main_image_alt = get_the_title();
$is_in_wishlist = is_user_logged_in() && class_exists(\App\WishlistHandler::class)
    ? \App\WishlistHandler::isProductInCurrentUserWishlists((int) $post_id)
    : false;

$related_ids = [];
if ($first_cat && isset($first_cat->term_id)) {
    $related_query = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 3,
        'post__not_in'   => [$post_id],
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => [$first_cat->term_id],
            ],
        ],
    ]);
    if ($related_query->have_posts()) {
        $related_ids = wp_list_pluck($related_query->posts, 'ID');
    }
    wp_reset_postdata();
}
if (empty($related_ids)) {
    $fallback = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 3,
        'post__not_in'   => [$post_id],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
    if ($fallback->have_posts()) {
        $related_ids = wp_list_pluck($fallback->posts, 'ID');
    }
    wp_reset_postdata();
}

$show_prices = is_user_logged_in() && function_exists('wc_get_product');
$related_items = [];
foreach ($related_ids as $pid) {
    $thumb_id_rel = get_post_thumbnail_id($pid);
    $img_url_rel  = $thumb_id_rel ? wp_get_attachment_image_url($thumb_id_rel, 'full') : '';
    $cat_label    = '';
    if (taxonomy_exists('product_cat')) {
        $terms_rel = get_the_terms($pid, 'product_cat');
        if ($terms_rel && !is_wp_error($terms_rel) && !empty($terms_rel)) {
            $cat_label = $terms_rel[0]->name;
        }
    }
    $related_item = [
        'image_url'      => $img_url_rel ?: '',
        'image_alt'      => get_the_title($pid),
        'category_label' => $cat_label,
        'title'          => get_the_title($pid),
        'url'            => get_permalink($pid),
        'link_text'      => $pdp_related_link_text,
        'image_bg_white' => true,
    ];
    if ($show_prices) {
        $wc_product = wc_get_product($pid);
        if ($wc_product && $wc_product->get_price_html()) {
            $related_item['price_html'] = $wc_product->get_price_html();
        }
    }
    $related_items[] = $related_item;
}

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
$check_icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0" aria-hidden="true"><path d="M10.5131 14.1195L8.17684 11.7832C8.01084 11.6172 7.80734 11.5342 7.56634 11.5342C7.32534 11.5342 7.11192 11.6256 6.92609 11.8082C6.76025 11.9781 6.67734 12.1833 6.67734 12.424C6.67734 12.6645 6.76225 12.8677 6.93209 13.0337L9.91034 16.037C10.0822 16.2068 10.2826 16.2917 10.5116 16.2917C10.7406 16.2917 10.942 16.2068 11.1158 16.037L17.0191 10.1277C17.2058 9.94506 17.2991 9.73423 17.2991 9.49523C17.2991 9.25623 17.2058 9.0439 17.0191 8.85823C16.8364 8.69223 16.6204 8.6134 16.3711 8.62173C16.1218 8.63006 15.9126 8.71723 15.7436 8.88323L10.5131 14.1195ZM12.0008 22.1495C10.6098 22.1495 9.297 21.8838 8.06234 21.3525C6.82784 20.8211 5.74959 20.0945 4.82759 19.1725C3.90559 18.2505 3.17892 17.1725 2.64759 15.9385C2.11625 14.7045 1.85059 13.3919 1.85059 12.0007C1.85059 10.5931 2.11625 9.2719 2.64759 8.03723C3.17892 6.80273 3.90525 5.72857 4.82659 4.81473C5.74792 3.90073 6.82575 3.17723 8.06009 2.64423C9.29442 2.11107 10.6073 1.84448 11.9988 1.84448C13.4068 1.84448 14.7284 2.1109 15.9636 2.64373C17.1986 3.17657 18.2728 3.89965 19.1863 4.81298C20.1 5.72632 20.8233 6.80032 21.3561 8.03498C21.8891 9.26965 22.1556 10.5913 22.1556 12C22.1556 13.3916 21.889 14.7047 21.3558 15.9392C20.8228 17.1737 20.0993 18.2517 19.1853 19.1732C18.2715 20.0947 17.1976 20.8211 15.9636 21.3525C14.7296 21.8838 13.4087 22.1495 12.0008 22.1495ZM12.0001 20.4462C14.3508 20.4462 16.3462 19.6233 17.9863 17.9775C19.6263 16.3315 20.4463 14.339 20.4463 12C20.4463 9.64932 19.6263 7.6539 17.9863 6.01373C16.3462 4.37373 14.3498 3.55373 11.9971 3.55373C9.66109 3.55373 7.66984 4.37373 6.02334 6.01373C4.377 7.6539 3.55384 9.65031 3.55384 12.003C3.55384 14.339 4.37675 16.3302 6.02259 17.9767C7.66859 19.6231 9.66109 20.4462 12.0001 20.4462Z" fill="#0C0A21"/></svg>';
?>
<section class="pdp max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y" aria-label="<?php esc_attr_e('Product details', 'boozed'); ?>">
	<!-- Breadcrumbs -->
	<nav class="pdp__breadcrumbs mb-6 md:mb-8" aria-label="<?php esc_attr_e('Breadcrumb', 'boozed'); ?>">
		<ol class="flex flex-wrap items-center gap-1 font-body text-body-sm text-brand-indigo">
			<li><a href="<?php echo esc_url($shop_url); ?>" class="hover:underline"><?php echo esc_html($pdp_breadcrumb_verhuur); ?></a></li>
			<?php if ($first_cat) : ?>
				<li><span class="text-brand-black/50" aria-hidden="true">/</span></li>
				<li><a href="<?php echo esc_url(add_query_arg('product_cat[]', $first_cat->slug, $shop_url)); ?>" class="hover:underline"><?php echo esc_html($first_cat->name); ?></a></li>
			<?php endif; ?>
			<li><span class="text-brand-black/50" aria-hidden="true">/</span></li>
			<li class="text-brand-black" aria-current="page"><?php echo esc_html(get_the_title()); ?></li>
		</ol>
	</nav>

	<div class="pdp__grid grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
		<!-- Left column: main image + tabs + more + related (6/12 cols) -->
		<div class="pdp__left lg:col-span-6 flex flex-col gap-6 md:gap-8">
			<?php if ($main_image_url) : ?>
				<div class="pdp__image relative aspect-[4/3] w-full rounded overflow-hidden border-2 border-black">
					<img src="<?php echo esc_url($main_image_url); ?>" alt="<?php echo esc_attr($main_image_alt); ?>" class="w-full h-full object-cover" loading="eager">
					<button
						type="button"
						class="pdp__wishlist-trigger<?php echo $is_in_wishlist ? ' is-active' : ''; ?>"
						data-wishlist-heart
						data-product-id="<?php echo esc_attr($post_id); ?>"
						aria-pressed="<?php echo $is_in_wishlist ? 'true' : 'false'; ?>"
						aria-label="<?php esc_attr_e('Toevoegen aan wenslijst', 'boozed'); ?>">
						<svg viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
							<path d="M178,42c-20.65,0-38.73,11.47-50,29.24C116.73,53.47,98.65,42,78,42A58.07,58.07,0,0,0,20,100c0,78.22,99.2,130.77,103.43,133a9.66,9.66,0,0,0,9.14,0c4.23-2.2,103.43-54.75,103.43-133A58.07,58.07,0,0,0,178,42Z"/>
						</svg>
					</button>
				</div>
			<?php endif; ?>

			<div class="pdp__tabs-wrap">
				<?php \App\Components::render('tabs', [
					'tabs'     => [
						['id' => 'beschrijving', 'label' => $pdp_tab_beschrijving],
						['id' => 'extra', 'label' => $pdp_tab_extra],
					],
					'active_id' => 'beschrijving',
					'class'    => 'pdp__tabs',
				]); ?>
				<div id="panel-beschrijving" class="pdp__panel mt-4 font-body text-body text-brand-black border border-t-0 border-brand-border rounded-b p-4" role="tabpanel" aria-labelledby="tab-beschrijving">
					<?php echo wp_kses_post(wpautop($product->get_short_description() ?: $pdp_geen_beschrijving)); ?>
				</div>
				<div id="panel-extra" class="pdp__panel hidden mt-4 font-body text-body text-brand-black border border-t-0 border-brand-border rounded-b p-4" role="tabpanel" aria-labelledby="tab-extra">
					<?php if ($has_dimensions) : ?>
						<p><?php echo esc_html($dimensions_long); ?></p>
					<?php endif; ?>
					<?php if ($product->get_weight()) : ?>
						<p><?php printf(esc_html__('Gewicht: %s kg', 'boozed'), esc_html($product->get_weight())); ?></p>
					<?php endif; ?>
					<?php if (!$has_dimensions && !$product->get_weight()) : ?>
						<p><?php echo esc_html($pdp_geen_extra_info); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="pdp__more">
				<h2 class="font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo mt-0 mb-4"><?php echo esc_html($pdp_meer_over_product); ?></h2>
				<div class="font-body text-body text-brand-black prose prose-p:mb-4 max-w-none">
					<?php echo wp_kses_post(wpautop($product->get_description() ?: $product->get_short_description() ?: '')); ?>
				</div>
			</div>

			<?php if (!empty($related_items)) : ?>
				<div class="pdp__related">
					<h2 class="font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo mt-0 mb-4"><?php echo esc_html($pdp_gerelateerde); ?></h2>
					<div class="flex gap-4 md:gap-6 overflow-x-auto pb-2 -mx-1">
						<?php foreach ($related_items as $item) : ?>
							<div class="product-lister__card shrink-0 w-[240px] md:w-[280px]">
								<?php \App\Components::render('product-card', $item); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<style>
				/* Reuse product-lister fixed height for related product cards (same as product_lister.php) */
				.pdp__related .product-lister__card { min-width: 0; }
				@media (min-width: 768px) {
					.pdp__related .product-lister__card {
						height: 435px;
					}
					.pdp__related .product-lister__card .product-card {
						height: 100%;
						display: flex;
						flex-direction: column;
						min-height: 0;
					}
					.pdp__related .product-lister__card .product-card__img {
						aspect-ratio: auto;
						flex: 1 1 0;
						min-height: 0;
					}
					.pdp__related .product-lister__card .product-card__img img {
						width: 100%;
						height: 100%;
						object-fit: cover;
					}
				}
				</style>
			<?php endif; ?>
		</div>

		<!-- Empty spacer (1/12 cols) -->
		<div class="hidden lg:block lg:col-span-1" aria-hidden="true"></div>

		<!-- Right column: product details (5/12 cols) -->
		<div class="pdp__right lg:col-span-5 flex flex-col gap-6 md:gap-8">
			<div class="pdp__header">
				<h1 class="pdp__title font-heading font-bold text-h1 md:text-h1-lg text-brand-purple mt-0 mb-1"><?php the_title(); ?></h1>
			</div>

			<div class="pdp__cta-price">
				<?php if (is_user_logged_in() && ($price_html = $product->get_price_html())) : ?>
					<p class="font-body text-body md:text-body-md text-brand-black mb-0">
						<span class="price"><?php echo wp_kses_post($price_html); ?></span>
					</p>
				<?php else : ?>
					<p class="font-body text-body md:text-body-md text-brand-black mb-3"><?php echo esc_html($pdp_benieuwd_prijs); ?></p>
					<?php \App\Components::render('button', [
						'variant'         => 'coral',
						'label'           => $pdp_cta_account,
						'href'            => $account_url,
						'icon_right_html' => $phosphor_chevron_right,
					]); ?>
					<p class="font-body text-body-sm text-brand-black mt-3 mb-0">
						<a href="<?php echo esc_url($login_url); ?>" class="text-brand-indigo underline underline-offset-2 hover:no-underline focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2 rounded-sm">
							<?php echo esc_html($pdp_cta_login); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>

			<div class="pdp__value-props-wrap rounded-lg p-4 md:p-5" style="background-color: #EEF0EE;">
				<ul class="pdp__value-props list-none pl-0 m-0 flex flex-col gap-2 font-body text-body-sm text-brand-black" aria-label="<?php echo esc_attr($pdp_usp_aria ?? __('Voordelen', 'boozed')); ?>">
					<li class="flex items-start gap-2"><?php echo $check_icon; ?> <span><?php echo esc_html($pdp_usp_1 ?? __('Alles voor je event onder één dak', 'boozed')); ?></span></li>
					<li class="flex items-start gap-2"><?php echo $check_icon; ?> <span><?php echo esc_html($pdp_usp_2 ?? __('Duurzaam & impact-gedreven', 'boozed')); ?></span></li>
					<li class="flex items-start gap-2"><?php echo $check_icon; ?> <span><?php echo esc_html($pdp_usp_3 ?? __('Het grootste assortiment van Nederland', 'boozed')); ?></span></li>
				</ul>
			</div>

			<div class="pdp__product-info">
				<h2 class="font-heading font-bold text-h5 md:text-h5-lg text-brand-indigo mt-0 mb-3"><?php echo esc_html($pdp_product_info_heading); ?></h2>
				<dl class="font-body text-body text-brand-black space-y-2">
					<?php if ($product->get_short_description()) : ?>
						<div>
							<dt class="font-medium text-brand-indigo"><?php echo esc_html($pdp_tab_beschrijving); ?></dt>
							<dd class="mt-0.5"><?php echo wp_kses_post(wpautop($product->get_short_description())); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ($has_dimensions) : ?>
						<div>
							<dt class="font-medium text-brand-indigo"><?php echo esc_html($pdp_label_afmetingen); ?></dt>
							<dd class="mt-0.5"><?php echo esc_html($dimensions_long); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ($product->get_weight()) : ?>
						<div>
							<dt class="font-medium text-brand-indigo"><?php echo esc_html($pdp_label_gewicht); ?></dt>
							<dd class="mt-0.5"><?php printf(esc_html__('%s kg', 'boozed'), esc_html($product->get_weight())); ?></dd>
						</div>
					<?php endif; ?>
					<?php if (!$product->get_short_description() && !$has_dimensions && !$product->get_weight()) : ?>
						<p class="text-brand-black/70"><?php echo esc_html($pdp_geen_extra_info); ?></p>
					<?php endif; ?>
				</dl>
			</div>

			<?php if (!empty($product_cats) || !empty($product_tags)) : ?>
				<div class="pdp__taxonomies flex flex-col gap-3">
					<?php if (!empty($product_cats)) : ?>
						<div>
							<span class="font-body text-body-sm font-medium text-brand-black block mb-2"><?php echo esc_html($pdp_categorieen); ?></span>
							<div class="flex flex-wrap gap-2">
								<?php foreach ($product_cats as $term) :
									if (is_wp_error($term) || !$term) continue;
								?>
									<a href="<?php echo esc_url(add_query_arg('product_cat[]', $term->slug, $shop_url)); ?>" class="inline-flex items-center h-10 font-body text-body-sm font-medium rounded px-4 py-2 bg-brand-indigo text-brand-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2"><?php echo esc_html($term->name); ?></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (!empty($product_tags)) : ?>
						<div>
							<span class="font-body text-body-sm font-medium text-brand-black block mb-2"><?php echo esc_html($pdp_tags); ?></span>
							<div class="flex flex-wrap gap-2">
								<?php foreach ($product_tags as $term) :
									if (is_wp_error($term) || !$term) continue;
								?>
									<a href="<?php echo esc_url(add_query_arg('product_tag[]', $term->slug, $shop_url)); ?>" class="inline-flex items-center h-10 font-body text-body-sm font-medium rounded px-4 py-2 bg-brand-indigo text-brand-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2"><?php echo esc_html($term->name); ?></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<!-- Maatwerk block: right column, underneath categories/tags -->
			<?php
			$maatwerk_img = is_array($pdp_maatwerk_image) && !empty($pdp_maatwerk_image['url'])
				? $pdp_maatwerk_image
				: (is_numeric($pdp_maatwerk_image) ? wp_get_attachment_image_src((int) $pdp_maatwerk_image, 'large') : null);
			$maatwerk_img_url = is_array($maatwerk_img) ? ($maatwerk_img['url'] ?? $maatwerk_img[0] ?? '') : '';
			$maatwerk_img_alt = is_array($pdp_maatwerk_image) && !empty($pdp_maatwerk_image['alt']) ? $pdp_maatwerk_image['alt'] : __('Maatwerk', 'boozed');
			?>
			<div class="pdp__customization mt-6 md:mt-8 border-t-2 border-b-2 border-brand-indigo p-6 -mx-1 md:mx-0 flex flex-col md:flex-row gap-4 md:gap-6">
				<?php if ($maatwerk_img_url) : ?>
					<div class="pdp__maatwerk-image aspect-[167/244] w-full max-w-[167px] md:w-[167px] md:flex-shrink-0 overflow-hidden">
						<img src="<?php echo esc_url($maatwerk_img_url); ?>" alt="<?php echo esc_attr($maatwerk_img_alt); ?>" class="w-full h-full object-cover" loading="lazy">
					</div>
				<?php endif; ?>
				<div class="pdp__maatwerk-content md:flex-1 flex flex-col justify-center">
					<h2 class="font-heading font-bold text-h5 md:text-h5-lg text-brand-black mt-0 mb-2"><?php echo esc_html($pdp_maatwerk_heading); ?></h2>
					<p class="font-body text-body text-brand-black mb-4"><?php echo esc_html($pdp_maatwerk_text); ?></p>
					<?php \App\Components::render('button', [
						'variant' => 'coral',
						'label'   => $pdp_maatwerk_btn,
						'href'    => $maatwerk_url,
					]); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
(function() {
	var wrap = document.querySelector('.pdp__tabs-wrap');
	if (!wrap) return;
	var tabs = wrap.querySelectorAll('[role="tab"]');
	var panels = wrap.querySelectorAll('.pdp__panel');
	function setActive(id) {
		tabs.forEach(function(t) {
			var isActive = (t.getAttribute('id') === 'tab-' + id);
			t.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});
		panels.forEach(function(p) {
			var match = p.id === 'panel-' + id;
			p.classList.toggle('hidden', !match);
		});
	}
	tabs.forEach(function(tab) {
		tab.addEventListener('click', function() {
			var id = this.getAttribute('id').replace('tab-', '');
			setActive(id);
		});
	});
})();
</script>
