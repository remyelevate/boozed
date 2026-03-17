<?php

/**
 * Vacature features section
 * Horizontal row of features: icon (50×50) + label (20px regular) on dark background.
 * Icons are pulled from Global Settings → Vacatures (one default icon per taxonomy).
 * Terms are read from the current vacature post's assigned taxonomies.
 */

$taxonomy_order = [ 'locatie', 'uren', 'niveau', 'team', 'dienstverband' ];
$items          = [];
$post_id        = get_the_ID();

if ($post_id) {
	$icon_cache = [];
	foreach ($taxonomy_order as $tax) {
		if (!isset($icon_cache[$tax])) {
			$icon_cache[$tax] = function_exists('get_field')
				? (int) get_field('vacature_icon_' . $tax, 'option')
				: 0;
		}
	}

	foreach ($taxonomy_order as $tax) {
		$terms = get_the_terms($post_id, $tax);
		if (!is_array($terms) || is_wp_error($terms)) {
			continue;
		}
		$icon_id = $icon_cache[$tax];
		foreach ($terms as $term) {
			$items[] = [
				'label'   => $term->name,
				'icon_id' => $icon_id,
			];
		}
	}
}

$items = array_filter($items, function ($item) {
	return $item['label'] !== '' || $item['icon_id'] > 0;
});

if (empty($items)) {
	return;
}
?>
<section class="vacature-features bg-brand-indigo py-10 md:py-14">
	<div class="vacature-features__inner max-w-section mx-auto px-4 md:px-section-x">
		<div class="vacature-features__list flex flex-wrap justify-center md:justify-between items-stretch gap-8 md:gap-6">
			<?php
			$fallback_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-white/60" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>';
			foreach ($items as $item) :
				$label   = $item['label'];
				$icon_id = (int) $item['icon_id'];
				$img_src = $icon_id ? wp_get_attachment_image_url($icon_id, 'medium') : '';
			?>
			<div class="vacature-features__item flex flex-col items-center text-center flex-1 min-w-[120px] max-w-[200px]">
				<div class="vacature-features__icon w-[50px] h-[50px] flex items-center justify-center shrink-0 mb-3">
					<?php if ($img_src) : ?>
						<img src="<?php echo esc_url($img_src); ?>" alt="" class="w-[50px] h-[50px] object-contain object-center" width="50" height="50" loading="lazy">
					<?php else : ?>
						<?php echo $fallback_icon_svg; ?>
					<?php endif; ?>
				</div>
				<?php if ($label !== '') : ?>
					<span class="vacature-features__label font-body text-[20px] font-normal text-brand-white leading-tight"><?php echo esc_html($label); ?></span>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
