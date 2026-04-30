<?php

/**
 * Project showcase section
 * Large full-width title (absolute, wraps over images), two images 534×696 on desktop, caption, WYSIWYG description.
 */

$title       = function_exists('get_sub_field') ? (string) get_sub_field('project_showcase_title') : '';
$image_1_id  = function_exists('get_sub_field') ? get_sub_field('project_showcase_image_1') : null;
$image_2_id  = function_exists('get_sub_field') ? get_sub_field('project_showcase_image_2') : null;
$caption     = function_exists('get_sub_field') ? (string) get_sub_field('project_showcase_caption') : '';
$description = function_exists('get_sub_field') ? get_sub_field('project_showcase_description') : '';

$image_1_url = $image_1_id ? wp_get_attachment_image_url($image_1_id, 'full') : '';
$image_2_url = $image_2_id ? wp_get_attachment_image_url($image_2_id, 'full') : '';
$has_images  = $image_1_url || $image_2_url;
?>
<section class="project-showcase max-w-section mx-auto px-4 py-10 md:px-0 md:py-section-y">
	<?php /* Title (absolute on desktop, wraps over images) + fixed-size images pushed right */ ?>
	<div class="project-showcase__header relative">
		<?php if ($title !== '') : ?>
			<h2 class="project-showcase__title font-heading font-bold text-[40px] md:text-[124px] leading-[1.2] text-brand-indigo max-w-none m-0 md:absolute md:-top-[232px] md:left-0 md:w-full md:z-10 md:pointer-events-none overflow-hidden whitespace-nowrap" aria-label="<?php echo esc_attr($title); ?>">
				<div class="inline-flex" style="animation: project-showcase-title-marquee 25s linear infinite;">
					<span class="pr-[0.25em]"><?php echo esc_html($title); ?></span>
					<span class="pr-[0.25em]" aria-hidden="true"><?php echo esc_html($title); ?></span>
				</div>
			</h2>
			<style>
				@keyframes project-showcase-title-marquee {
					0% { transform: translateX(0); }
					100% { transform: translateX(-50%); }
				}
			</style>
		<?php endif; ?>

		<?php if ($has_images) : ?>
			<div class="project-showcase__images grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 md:mt-[232px] md:pl-[179px] md:pr-[179px] mb-6 md:mb-16">
				<?php if ($image_1_url) : ?>
					<div class="project-showcase__image-wrap aspect-[534/696] overflow-hidden bg-brand-border">
						<img src="<?php echo esc_url($image_1_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
					</div>
				<?php endif; ?>
				<?php if ($image_2_url) : ?>
					<div class="project-showcase__image-wrap aspect-[534/696] overflow-hidden bg-brand-border">
						<img src="<?php echo esc_url($image_2_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php /* Caption + description: 179px horizontal padding on desktop */ ?>
	<div class="project-showcase__content px-0 md:pl-[179px] md:pr-[179px]">
		<?php if ($caption !== '') : ?>
			<p class="project-showcase__caption font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo mb-6">
				<?php echo esc_html($caption); ?>
			</p>
		<?php endif; ?>

		<?php if ($description) : ?>
			<div class="project-showcase__description prose prose-lg font-body text-body-md text-brand-black max-w-none">
				<?php echo wp_kses_post($description); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
