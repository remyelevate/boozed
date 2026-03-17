<?php
/**
 * News card – reusable card for news/post listings.
 * Props: permalink, title, excerpt, category_label, image_url, class (optional).
 * Entire card is a link; custom cursor with label is shown by news lister section on hover.
 */
$args = $args ?? [];

$permalink      = $args['permalink'] ?? '';
$title          = $args['title'] ?? '';
$excerpt        = $args['excerpt'] ?? '';
$category_label = $args['category_label'] ?? '';
$image_url      = $args['image_url'] ?? '';
$class          = $args['class'] ?? '';

$card_class = 'news-card group flex flex-col bg-brand-white border border-brand-border overflow-hidden no-underline text-inherit transition-shadow hover:shadow-md hover:border-brand-purple/20 ' . $class;
?>
<a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr(trim($card_class)); ?>">
	<div class="news-card__img relative aspect-[4/3] w-full bg-brand-border overflow-hidden">
		<?php if ($image_url !== '') : ?>
			<img src="<?php echo esc_url($image_url); ?>" alt="" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
		<?php endif; ?>
	</div>
	<div class="news-card__content p-4 md:p-5 flex flex-col gap-2">
		<?php if ($category_label !== '') : ?>
			<span class="news-card__category font-body text-body-sm text-brand-indigo/60"><?php echo esc_html($category_label); ?></span>
		<?php endif; ?>
		<?php if ($title !== '') : ?>
			<h3 class="news-card__title font-heading font-bold text-h4 md:text-h4-lg text-brand-purple mt-0"><?php echo esc_html($title); ?></h3>
		<?php endif; ?>
		<?php if ($excerpt !== '') : ?>
			<p class="news-card__excerpt font-body text-body text-brand-black mb-0 line-clamp-3"><?php echo esc_html($excerpt); ?> <span class="underline"><?php esc_html_e('Lees meer', 'boozed'); ?></span></p>
		<?php endif; ?>
	</div>
</a>
