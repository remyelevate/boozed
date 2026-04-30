<?php

/**
 * Project hours / Cijfers section
 * Full-bleed layout (no horizontal padding). Left column: title, text, CTA.
 * Right column: stat rows with hover-reveal image (same pattern as projects_lister).
 */

$heading   = function_exists('get_sub_field') ? (string) get_sub_field('project_hours_heading') : '';
$content   = function_exists('get_sub_field') ? get_sub_field('project_hours_content') : '';
$cta_label = function_exists('get_sub_field') ? (string) get_sub_field('project_hours_cta_label') : '';
$cta_url   = function_exists('get_sub_field') ? (string) get_sub_field('project_hours_cta_url') : '';
$rows      = function_exists('get_sub_field') ? get_sub_field('project_hours_rows') : [];
$rows      = is_array($rows) ? array_slice($rows, 0, 3) : [];

$show_cta   = $cta_url !== '' && $cta_label !== '';
$has_left   = $heading !== '' || $content || $show_cta;
$has_rows   = is_array($rows) && !empty($rows);

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>

<section class="project-hours max-w-section mx-auto pb-section-y">
	<div class="project-hours__grid grid grid-cols-1 md:grid-cols-[491px_1fr] min-h-0 md:items-start">
		<?php if ($has_left) : ?>
		<div class="project-hours__left bg-brand-indigo w-full md:w-[491px] md:h-[421px] md:shrink-0 px-4 py-10 md:px-10 md:py-10 flex flex-col justify-center">
			<?php if ($heading !== '') : ?>
				<h2 class="project-hours__title font-heading font-bold text-h2 md:text-h2-lg text-brand-white m-0 mb-6">
					<?php echo esc_html($heading); ?>
				</h2>
			<?php endif; ?>
			<?php if ($content) : ?>
				<div class="project-hours__content font-body text-body-md text-brand-white/90 mb-6 prose prose-invert max-w-none">
					<?php echo wp_kses_post(wpautop($content)); ?>
				</div>
			<?php endif; ?>
			<?php if ($show_cta) : ?>
				<div class="project-hours__actions">
					<?php
					\App\Components::render('button', [
						'variant'         => 'coral',
						'label'           => $cta_label,
						'href'            => $cta_url,
						'icon_right_html' => $phosphor_chevron_right,
						'class'           => '!bg-brand-coral',
					]);
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ($has_rows) : ?>
		<div class="project-hours__right ph-table border-t border-brand-black/10 md:h-[421px] md:flex md:flex-col <?php echo $has_left ? '' : 'md:col-span-2'; ?>">
			<?php foreach ($rows as $row) : ?>
				<?php
				$number = isset($row['number']) ? (string) $row['number'] : '';
				$label = isset($row['label']) ? (string) $row['label'] : '';
				$img_id = isset($row['image']) ? (int) $row['image'] : 0;
				$img_url = $img_id ? wp_get_attachment_image_url($img_id, 'full') : '';
				?>
				<div class="ph-table__row relative grid items-center border-b border-brand-black/10 transition-colors duration-200 group"
				     <?php if ($img_url) : ?>data-featured-url="<?php echo esc_url($img_url); ?>"<?php endif; ?>>

					<span class="font-heading font-bold text-h2 md:text-h2-lg text-brand-black group-hover:text-brand-white transition-colors"><?php echo esc_html($number); ?></span>
					<span class="font-body text-body-md text-brand-black group-hover:text-brand-white/90 transition-colors"><?php echo esc_html($label); ?></span>

					<?php if ($img_url) : ?>
					<div class="ph-table__image absolute top-1/2 -translate-y-1/2 overflow-hidden opacity-0 scale-95 transition-all duration-300 z-10 shadow-2xl group-hover:opacity-100 group-hover:scale-100">
						<img src="<?php echo esc_url($img_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
					</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<style>
/* Project hours – row layout (scoped, no horizontal padding) */
.project-hours .ph-table__row {
	display: grid;
	grid-template-columns: 80px 1fr;
	gap: 0.5rem 1.5rem;
	min-height: 80px;
	padding: 1.25rem 24px;
}
@media (min-width: 768px) {
	.project-hours .ph-table__row {
		flex: 1;
		min-height: 0;
		grid-template-columns: 100px 1fr;
		padding: 0 70px;
	}
}
/* Row hover */
.project-hours .ph-table__row:hover {
	background-color: #312783;
}
/* Featured image – positioned at right edge */
.project-hours .ph-table__image {
	right: -20px;
	width: 240px;
	height: 300px;
}
@media (min-width: 768px) {
	.project-hours .ph-table__image {
		right: -40px;
		width: 388px;
		height: 484px;
	}
}
/* Mobile: stack, hide image */
@media (max-width: 767px) {
	.project-hours .ph-table__row {
		grid-template-columns: 1fr !important;
		gap: 0.25rem;
	}
	.project-hours .ph-table__image {
		display: none;
	}
}
</style>
