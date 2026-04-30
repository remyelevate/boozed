<?php

/**
 * Banner image section
 * Container-wide image, 800px high on desktop. No padding.
 * Toggle: featured image (post thumbnail) or custom image.
 */

$raw          = function_exists('get_sub_field') ? get_sub_field('banner_use_featured') : null;
$use_featured = ($raw === null || $raw === '') ? true : (bool) $raw;
$post_id     = get_the_ID();
$thumb_id    = $post_id ? get_post_thumbnail_id($post_id) : null;
$image_id    = $use_featured ? $thumb_id : (function_exists('get_sub_field') ? get_sub_field('banner_image') : null);
$image_url   = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';

if (!$image_url) {
    return;
}
?>
<section class="project-featured-image w-full" aria-hidden="true">
	<div class="project-featured-image__inner max-w-section mx-auto w-full">
		<div class="project-featured-image__image-wrap w-full h-[50vw] min-h-[280px] md:h-[800px] overflow-hidden bg-brand-border">
			<img
				src="<?php echo esc_url($image_url); ?>"
				alt=""
				class="w-full h-full object-cover"
				loading="lazy"
			>
		</div>
	</div>
</section>
