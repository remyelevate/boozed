<?php

/**
 * Features section
 * Two columns: left = intro + feature list; right = image, title, description.
 * Hover a feature to show its image on the right. Default image shown when no feature is hovered.
 */

$intro_heading   = function_exists('get_sub_field') ? (string) get_sub_field('features_intro_heading') : '';
$intro_body      = function_exists('get_sub_field') ? (string) get_sub_field('features_intro_body') : '';
$default_img_id  = function_exists('get_sub_field') ? (int) get_sub_field('features_default_image') : 0;
$default_title   = function_exists('get_sub_field') ? (string) get_sub_field('features_default_title') : '';
$default_desc    = function_exists('get_sub_field') ? (string) get_sub_field('features_default_description') : '';
$features_items  = function_exists('get_sub_field') ? array_slice((array) get_sub_field('features_items'), 0, 4) : [];

$default_img_url = $default_img_id ? wp_get_attachment_image_url($default_img_id, 'large') : '';
$placeholder_img = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>
<section class="features max-w-section mx-auto px-4 py-10 md:px-0 md:py-section-y"
         data-default-image="<?php echo esc_url($default_img_url ?: $placeholder_img); ?>"
         data-default-title="<?php echo esc_attr($default_title); ?>">
	<div class="features__grid grid grid-cols-1 md:grid-cols-2 gap-0 md:items-stretch">
		<div class="features__left flex flex-col md:pl-section-x">
			<div class="features__intro pr-6 md:pr-section-x shrink-0">
				<?php if ($intro_heading !== '') : ?>
					<h2 class="features__intro-heading font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo mb-4">
						<?php echo esc_html($intro_heading); ?>
					</h2>
				<?php endif; ?>
				<?php if ($intro_body !== '') : ?>
					<div class="features__intro-body font-body text-body text-brand-indigo/80 mb-8 prose max-w-none prose-p:mb-3 prose-p:last:mb-0">
						<?php echo wp_kses_post($intro_body); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="features__list flex flex-1 flex-col min-h-0">
				<?php foreach ($features_items as $item) :
					$label   = isset($item['label']) ? (string) $item['label'] : '';
					$img_id  = isset($item['image']) ? (int) $item['image'] : 0;
					$img_url = $img_id ? wp_get_attachment_image_url($img_id, 'large') : '';
					if ($label === '') continue;
				?>
				<div class="features__item flex items-center justify-between border-t border-brand-border transition-colors duration-200 cursor-default"
				     data-feature-image="<?php echo esc_url($img_url); ?>"
				     data-feature-title="<?php echo esc_attr($label); ?>">
					<span class="features__item-label font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo"><?php echo esc_html($label); ?></span>
					<span class="features__item-arrow text-brand-coral"><?php echo $phosphor_chevron_right; ?></span>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="features__desc-store hidden" aria-hidden="true">
				<div class="features__desc-default"><?php echo wp_kses_post($default_desc); ?></div>
				<?php foreach ($features_items as $item) :
					$label = isset($item['label']) ? (string) $item['label'] : '';
					$fdesc = isset($item['description']) ? (string) $item['description'] : '';
					if ($label === '') {
						continue;
					}
					?>
				<div class="features__desc-item"><?php echo wp_kses_post($fdesc); ?></div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="features__right flex flex-col bg-brand-indigo mt-8 md:mt-0 min-h-0">
			<div class="features__image-wrap flex-1 min-h-0 overflow-hidden bg-brand-black/20">
				<img src="<?php echo esc_url($default_img_url ?: $placeholder_img); ?>" alt="" class="features__image w-full h-full object-cover transition-opacity duration-300" loading="lazy">
			</div>
			<div class="features__right-content flex-shrink-0 p-[60px]">
				<h3 class="features__title font-heading font-bold text-h5 md:text-h5-lg text-brand-white mb-4">
					<?php echo esc_html($default_title); ?>
				</h3>
				<div class="features__description font-body text-body text-brand-white/90 prose prose-invert max-w-none">
					<?php echo wp_kses_post($default_desc); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_features_script_printed'])) : $GLOBALS['boozed_features_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		document.querySelectorAll('.features').forEach(function(section) {
			var leftCol = section.querySelector('.features__left');
			var items = section.querySelectorAll('.features__item');
			var img = section.querySelector('.features__image');
			var titleEl = section.querySelector('.features__title');
			var descEl = section.querySelector('.features__description');
			if (!leftCol || !img) return;

			var defaultImage = section.getAttribute('data-default-image') || '';
			var defaultTitle = section.getAttribute('data-default-title') || '';
			var defaultDescSource = section.querySelector('.features__desc-default');
			var descFragments = section.querySelectorAll('.features__desc-item');

			function resetToDefault() {
				img.src = defaultImage;
				if (titleEl) titleEl.textContent = defaultTitle;
				if (descEl && defaultDescSource) descEl.innerHTML = defaultDescSource.innerHTML;
				items.forEach(function(it) { it.classList.remove('features__item--active'); });
			}

			function showFeature(item) {
				var url = item.getAttribute('data-feature-image') || defaultImage;
				var title = item.getAttribute('data-feature-title') || defaultTitle;
				var idx = Array.prototype.indexOf.call(items, item);
				img.src = url;
				if (titleEl) titleEl.textContent = title;
				if (descEl && descFragments[idx]) descEl.innerHTML = descFragments[idx].innerHTML;
				else if (descEl && defaultDescSource) descEl.innerHTML = defaultDescSource.innerHTML;
				items.forEach(function(it) { it.classList.remove('features__item--active'); });
				item.classList.add('features__item--active');
			}

			items.forEach(function(item) {
				item.addEventListener('mouseenter', function() {
					showFeature(item);
				});
			});

			leftCol.addEventListener('mouseleave', function() {
				resetToDefault();
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

<style>
/* Feature rows: minimum height for tap/hover target; fill list equally so last border aligns */
.features .features__item {
	flex: 1 1 0;
	min-height: 72px;
	padding: 20px 24px;
}
@media (min-width: 768px) {
	.features .features__item {
		min-height: 80px;
		padding: 24px 24px 24px 68px;
	}
}

/* Active state: purple bg, white text */
.features .features__item--active {
	background-color: #312783;
}
.features .features__item--active .features__item-label,
.features .features__item--active .features__item-arrow {
	color: #FFFFFF;
}

/* Lines extend full width – remove left padding so border reaches edge to edge */
.features .features__list {
	margin-left: -24px;
	margin-right: 0;
}
@media (min-width: 768px) {
	.features .features__list {
		margin-left: -68px;
		margin-right: 0;
	}
}

/* Right column: fixed height on desktop; image area fills remaining space */
@media (min-width: 768px) {
	.features .features__right {
		height: 744px;
		min-height: 0;
	}
	.features .features__image-wrap {
		flex: 1 1 0;
		min-height: 0;
	}
}
/* Fallback: keep aspect ratio on small screens so image has proportional height */
@media (max-width: 767px) {
	.features .features__image-wrap {
		aspect-ratio: 16 / 10;
	}
}
</style>
