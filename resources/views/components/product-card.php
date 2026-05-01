<?php
/**
 * Product card – design system partial for product sliders/lists.
 * Options: image_url, image_alt, category_label, title, url, link_text, show_link_label, price_html, class
 * Card always links to the PDP when url is provided. link_text controls the visible "Bekijken >" label when show_link_label is true.
 */
$args = $args ?? [];

$image_url       = $args['image_url'] ?? '';
$image_alt       = $args['image_alt'] ?? '';
$category_label  = $args['category_label'] ?? '';
$show_category   = array_key_exists('show_category', $args) ? (bool) $args['show_category'] : true;
$title           = $args['title'] ?? '';
$url             = $args['url'] ?? '';
$link_text       = isset($args['link_text']) ? (string) $args['link_text'] : '';
$show_link_label = !empty($args['show_link_label']);
$class           = $args['class'] ?? '';
$img_aspect_class = $args['img_aspect_class'] ?? 'aspect-square';
$image_bg_white  = !empty($args['image_bg_white']);
$img_bg_class    = $image_bg_white ? 'bg-white' : 'bg-brand-nude';
$price_html      = $args['price_html'] ?? '';

$is_link = $url !== '';
$show_visible_link = $is_link && $show_link_label && $link_text !== '';
$card_class = 'product-card flex flex-col bg-brand-white border border-brand-border shadow-sm overflow-hidden transition-shadow hover:shadow-md hover:border-brand-indigo/20 ' . $class;
?>
<?php if ($is_link && !$show_visible_link) : ?>
<a href="<?php echo esc_url($url); ?>" class="<?php echo esc_attr($card_class); ?> h-full">
<?php else : ?>
<div class="<?php echo esc_attr($card_class); ?> h-full">
<?php endif; ?>
	<div class="product-card__img <?php echo esc_attr($img_aspect_class); ?> w-full <?php echo esc_attr($img_bg_class); ?> overflow-hidden">
		<?php if ($image_url !== '') : ?>
			<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ?: $title); ?>" class="w-full h-full object-cover" loading="lazy">
		<?php endif; ?>
	</div>
	<div class="product-card__content p-4 md:p-5 flex flex-col gap-1">
		<?php if ($show_category && $category_label !== '') : ?>
			<span class="product-card__category font-body text-body-sm text-brand-indigo/60"><?php echo esc_html($category_label); ?></span>
		<?php endif; ?>
		<?php if ($title !== '') : ?>
			<h3 class="product-card__title font-heading font-bold text-body-md md:text-body-lg text-brand-indigo line-clamp-2 min-h-[3.25rem]"><?php echo esc_html($title); ?></h3>
		<?php endif; ?>
		<?php if ($price_html !== '') : ?>
			<span class="product-card__price mt-auto font-body text-body-sm font-medium text-brand-black"><?php echo wp_kses_post($price_html); ?></span>
		<?php endif; ?>
		<?php if ($show_visible_link) : ?>
			<a href="<?php echo esc_url($url); ?>" class="product-card__link-label inline-flex items-center gap-1 font-body text-body-sm font-medium text-brand-purple hover:text-brand-indigo focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 rounded mt-1 self-start">
				<?php echo esc_html($link_text); ?>
			</a>
		<?php endif; ?>
	</div>
<?php if ($is_link && !$show_visible_link) : ?>
</a>
<?php else : ?>
</div>
<?php endif; ?>
