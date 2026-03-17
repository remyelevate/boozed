<?php

/**
 * Request Account section
 * Two columns: left = image (e.g. person with abstract background), right = CF7 form.
 * Design system: max-w-section, brand-purple heading, white form card, coral submit.
 */

$image_id       = function_exists('get_sub_field') ? get_sub_field('request_account_image') : null;
$heading        = function_exists('get_sub_field') ? (string) get_sub_field('request_account_heading') : '';
$form_shortcode = function_exists('get_sub_field') ? (string) get_sub_field('request_account_form_shortcode') : '';

$heading      = $heading ?: 'Vul je gegevens in';
$image_url   = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$has_image   = $image_url !== '';
?>
<section class="section-request-account bg-brand-white">
	<div class="section-request-account__inner max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
		<div class="section-request-account__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
			<?php if ($has_image) : ?>
				<div class="section-request-account__left order-2 lg:order-1 relative overflow-hidden rounded-lg bg-brand-border" style="aspect-ratio: 1 / 1;">
					<img src="<?php echo esc_url($image_url); ?>" alt="" class="block w-full h-full object-cover" loading="lazy">
				</div>
			<?php endif; ?>

			<div class="section-request-account__right order-1 lg:order-2 <?php echo $has_image ? '' : 'lg:col-span-2'; ?>">
				<div class="section-request-account__form-card w-full max-w-xl">
					<h2 class="section-request-account__heading font-heading font-bold text-h4 md:text-h3 text-brand-purple mt-0 mb-6"><?php echo esc_html($heading); ?></h2>
					<?php if ($form_shortcode !== '') : ?>
						<div class="section-request-account__form">
							<?php echo do_shortcode($form_shortcode); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
