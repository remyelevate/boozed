<?php

/**
 * Intake process section
 * "Hoe werkt solliciteren bij Boozed" – title (ticker/marquee), subtitle/CTA, two blocks:
 * Block 1: image left, steps 1–2 right.
 * Block 2: steps 3–4 left, image right.
 */

$title         = function_exists('get_sub_field') ? (string) get_sub_field('intake_process_title') : '';
$subtitle      = function_exists('get_sub_field') ? (string) get_sub_field('intake_process_subtitle') : '';
$button_label  = function_exists('get_sub_field') ? (string) get_sub_field('intake_process_button_label') : '';
$button_url    = function_exists('get_sub_field') ? (string) get_sub_field('intake_process_button_url') : '';
$image_1_id    = function_exists('get_sub_field') ? (int) get_sub_field('intake_process_image_1') : 0;
$image_2_id    = function_exists('get_sub_field') ? (int) get_sub_field('intake_process_image_2') : 0;
$steps_raw     = function_exists('get_sub_field') ? (array) get_sub_field('intake_process_steps') : [];

$image_1_url = $image_1_id ? wp_get_attachment_image_url($image_1_id, 'large') : '';
$image_2_url = $image_2_id ? wp_get_attachment_image_url($image_2_id, 'large') : '';
$show_button = $button_url !== '' && $button_label !== '';

$steps = [];
foreach (array_slice($steps_raw, 0, 4) as $row) {
	$step_title   = isset($row['title']) ? trim((string) $row['title']) : '';
	$step_content = isset($row['content']) ? trim((string) $row['content']) : '';
	$steps[] = [
		'title'   => $step_title,
		'content' => $step_content,
	];
}

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>
<section class="intake-process max-w-section mx-auto px-4 py-4 md:px-section-x md:py-section-y bg-brand-white overflow-x-hidden">
	<?php if ($title !== '') : ?>
		<div class="intake-process__title-wrap w-full min-w-[100vw] overflow-x-hidden overflow-y-visible pointer-events-none pt-6 md:pt-8">
			<div class="intake-process__title-inner flex whitespace-nowrap will-change-transform pl-4 md:pl-section-x" style="margin-left: max(0px, calc((100% - 1920px) / 2));">
				<h2 class="intake-process__title font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] text-brand-black inline-block pr-[1em]">
					<?php echo esc_html($title); ?>
				</h2>
				<span class="intake-process__title-dupe font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] text-brand-black inline-block pr-[1em] hidden" aria-hidden="true">
					<?php echo esc_html($title); ?>
				</span>
			</div>
		</div>
	<?php endif; ?>

	<div class="intake-process__content">
		<?php if ($subtitle !== '') : ?>
			<p class="intake-process__subtitle font-body text-body-md md:text-body-lg text-brand-black mb-6">
				<?php echo esc_html($subtitle); ?>
			</p>
		<?php endif; ?>
		<?php if ($show_button) : ?>
			<div class="intake-process__cta mb-8 md:mb-10">
				<?php
				\App\Components::render('button', [
					'variant'          => 'coral',
					'label'            => $button_label,
					'href'             => $button_url,
					'icon_right_html'  => $phosphor_chevron_right,
					'class'            => '!bg-brand-coral',
				]);
				?>
			</div>
		<?php endif; ?>

		<?php
		// Block 1: image left, steps 1–2 right
		$steps_block_1 = array_slice($steps, 0, 2);
		$steps_block_2 = array_slice($steps, 2, 2);
		?>

		<?php if ($image_1_url || ! empty($steps_block_1)) : ?>
		<div class="intake-process__block grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 xl:gap-16 items-center mb-12 md:mb-16">
			<?php if ($image_1_url) : ?>
				<div class="intake-process__image overflow-hidden bg-brand-border aspect-[4/5] lg:aspect-[535/688] order-2 lg:order-1">
					<img src="<?php echo esc_url($image_1_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
				</div>
			<?php endif; ?>
			<div class="intake-process__steps order-1 <?php echo $image_1_url ? 'lg:order-2' : ''; ?> space-y-6 md:space-y-8">
				<?php foreach ($steps_block_1 as $step) : ?>
					<div class="intake-process__step">
						<?php if ($step['title'] !== '') : ?>
							<h3 class="intake-process__step-title font-heading font-bold text-h4 md:text-h4-lg text-brand-black mb-2">
								<?php echo esc_html($step['title']); ?>
							</h3>
						<?php endif; ?>
						<?php if ($step['content'] !== '') : ?>
							<div class="intake-process__step-body font-body text-body md:text-body-md text-brand-black/90 leading-relaxed">
								<?php echo wp_kses_post(nl2br($step['content'])); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($image_2_url || ! empty($steps_block_2)) : ?>
		<div class="intake-process__block grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 xl:gap-16 items-center">
			<div class="intake-process__steps space-y-6 md:space-y-8 order-2 lg:order-1">
				<?php foreach ($steps_block_2 as $step) : ?>
					<div class="intake-process__step">
						<?php if ($step['title'] !== '') : ?>
							<h3 class="intake-process__step-title font-heading font-bold text-h4 md:text-h4-lg text-brand-black mb-2">
								<?php echo esc_html($step['title']); ?>
							</h3>
						<?php endif; ?>
						<?php if ($step['content'] !== '') : ?>
							<div class="intake-process__step-body font-body text-body md:text-body-md text-brand-black/90 leading-relaxed">
								<?php echo wp_kses_post(nl2br($step['content'])); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ($image_2_url) : ?>
				<div class="intake-process__image overflow-hidden bg-brand-border aspect-[4/5] lg:aspect-[535/688] order-1 lg:order-2">
					<img src="<?php echo esc_url($image_2_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
				</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php if ($title !== '' && empty($GLOBALS['boozed_intake_process_ticker_script_printed'])) : $GLOBALS['boozed_intake_process_ticker_script_printed'] = true; ?>
<script>
(function() {
	function runIntakeProcessTicker() {
		document.querySelectorAll('.intake-process__title-wrap').forEach(function(wrap) {
			var inner = wrap.querySelector('.intake-process__title-inner');
			var dupe = wrap.querySelector('.intake-process__title-dupe');
			if (!inner) return;
			if (dupe) dupe.classList.remove('hidden');
			var innerWidth = inner.scrollWidth;
			var viewportWidth = window.innerWidth;
			var shouldTick = innerWidth > viewportWidth;
			inner.classList.toggle('intake-process__title-inner--ticker', shouldTick);
			if (dupe) dupe.classList.toggle('hidden', !shouldTick);
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			runIntakeProcessTicker();
			if (document.fonts && document.fonts.ready) document.fonts.ready.then(runIntakeProcessTicker);
			setTimeout(runIntakeProcessTicker, 100);
		});
	} else {
		runIntakeProcessTicker();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(runIntakeProcessTicker);
		setTimeout(runIntakeProcessTicker, 100);
	}
	window.addEventListener('resize', runIntakeProcessTicker);
})();
</script>
<?php endif; ?>
