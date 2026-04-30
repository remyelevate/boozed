<?php

/**
 * Vacature content section
 * Fields: title (ticker), perks repeater (icon, title, content), banner image, left/right column (title + wysiwyg)
 */

$title           = function_exists('get_sub_field') ? (string) get_sub_field('vacature_content_title') : '';
$perks           = function_exists('get_sub_field') ? (array) get_sub_field('vacature_content_perks') : [];
$banner_id       = function_exists('get_sub_field') ? (int) get_sub_field('vacature_content_banner') : 0;
$left_title      = function_exists('get_sub_field') ? (string) get_sub_field('vacature_content_left_title') : '';
$left_content    = function_exists('get_sub_field') ? get_sub_field('vacature_content_left_content') : '';
$right_title     = function_exists('get_sub_field') ? (string) get_sub_field('vacature_content_right_title') : '';
$right_content   = function_exists('get_sub_field') ? get_sub_field('vacature_content_right_content') : '';

$has_any = $title !== '' || !empty($perks) || $banner_id > 0 || $left_title !== '' || $left_content !== '' || $right_title !== '' || $right_content !== '';
if (!$has_any) {
    return;
}

$title_color = 'text-brand-indigo';
?>
<section class="section section-vacature-content bg-brand-white overflow-x-hidden py-section-y">
	<?php if ($title !== '') : ?>
		<div class="page-header__title-wrap w-full min-w-[100vw] overflow-x-hidden overflow-y-visible pointer-events-none mb-8">
			<div class="page-header__title-inner flex whitespace-nowrap will-change-transform pl-4 md:pl-section-x" style="margin-left: max(0px, calc((100% - 1920px) / 2));">
				<h2 class="page-header__title font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] <?php echo esc_attr($title_color); ?> inline-block pr-[1em]">
					<?php echo esc_html($title); ?>
				</h2>
				<span class="page-header__title-dupe font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] <?php echo esc_attr($title_color); ?> inline-block pr-[1em] hidden" aria-hidden="true">
					<?php echo esc_html($title); ?>
				</span>
			</div>
		</div>
	<?php endif; ?>

	<?php if (!empty($perks)) : ?>
		<div class="max-w-section mx-auto px-section-x pt-0 pb-section-y">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6">
				<?php foreach ($perks as $perk) :
					$icon_id  = isset($perk['icon']) ? (int) $perk['icon'] : 0;
					$p_title = isset($perk['title']) ? (string) $perk['title'] : '';
					$p_content = isset($perk['content']) ? $perk['content'] : '';
					if ($p_title === '' && $p_content === '') continue;
				?>
					<div class="vacature-content__perk flex flex-col">
						<?php if ($icon_id > 0) : ?>
							<div class="vacature-content__perk-icon w-12 h-12 mb-4 flex items-center justify-center shrink-0 text-brand-indigo">
								<?php echo wp_get_attachment_image($icon_id, 'thumbnail', false, ['class' => 'max-w-full max-h-full object-contain']); ?>
							</div>
						<?php endif; ?>
						<?php if ($p_title !== '') : ?>
							<h3 class="font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo mb-2"><?php echo esc_html($p_title); ?></h3>
						<?php endif; ?>
						<?php if ($p_content !== '') : ?>
							<div class="font-body text-body text-brand-indigo prose max-w-none"><?php echo wp_kses_post($p_content); ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($banner_id > 0) : ?>
		<div class="vacature-content__banner max-w-section mx-auto px-section-x w-full overflow-hidden">
			<?php echo wp_get_attachment_image($banner_id, 'full', false, ['class' => 'w-full h-auto object-cover', 'loading' => 'lazy']); ?>
		</div>
	<?php endif; ?>

	<?php if ($left_title !== '' || $left_content !== '' || $right_title !== '' || $right_content !== '') : ?>
		<div class="max-w-section mx-auto px-section-x py-section-y">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
				<div class="vacature-content__col prose max-w-none md:pr-[25%]">
					<?php if ($left_title !== '') : ?>
						<h3 class="font-heading font-bold text-h3 md:text-h3-lg text-brand-indigo mb-4"><?php echo esc_html($left_title); ?></h3>
					<?php endif; ?>
					<?php if ($left_content !== '') : ?>
						<div class="font-body text-body-md text-brand-indigo"><?php echo wp_kses_post($left_content); ?></div>
					<?php endif; ?>
				</div>
				<div class="vacature-content__col prose max-w-none">
					<?php if ($right_title !== '') : ?>
						<h3 class="font-heading font-bold text-h3 md:text-h3-lg text-brand-indigo mb-4"><?php echo esc_html($right_title); ?></h3>
					<?php endif; ?>
					<?php if ($right_content !== '') : ?>
						<div class="font-body text-body-md text-brand-indigo"><?php echo wp_kses_post($right_content); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if ($title !== '' && empty($GLOBALS['boozed_page_header_ticker_script_printed'])) : $GLOBALS['boozed_page_header_ticker_script_printed'] = true; ?>
<script>
(function() {
	function runPageHeaderTicker() {
		document.querySelectorAll('.page-header__title-wrap').forEach(function(wrap) {
			var inner = wrap.querySelector('.page-header__title-inner');
			var dupe = wrap.querySelector('.page-header__title-dupe');
			if (!inner) return;
			if (dupe) dupe.classList.remove('hidden');
			var innerWidth = inner.scrollWidth;
			var viewportWidth = window.innerWidth;
			var shouldTick = innerWidth > viewportWidth;
			inner.classList.toggle('page-header__title-inner--ticker', shouldTick);
			if (dupe) dupe.classList.toggle('hidden', !shouldTick);
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			runPageHeaderTicker();
			if (document.fonts && document.fonts.ready) document.fonts.ready.then(runPageHeaderTicker);
			setTimeout(runPageHeaderTicker, 100);
		});
	} else {
		runPageHeaderTicker();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(runPageHeaderTicker);
		setTimeout(runPageHeaderTicker, 100);
	}
	window.addEventListener('resize', runPageHeaderTicker);
})();
</script>
<?php endif; ?>
