<?php
/**
 * Sticky apply banner for vacancy detail pages.
 * Fixed at bottom: job title on left, "Solliciteer voor deze functie" CTA on right.
 */

if (!is_singular('vacature')) {
    return;
}

$title = get_the_title();
$apply_url = function_exists('get_field') ? get_field('apply_url') : '';
if (empty($apply_url)) {
    $apply_url = get_permalink() . '#solliciteren';
}
?>
<aside class="vacature-apply-banner fixed bottom-0 left-0 right-0 z-[100] px-4 md:px-section-x py-4 md:py-5 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 md:gap-4 pb-[max(1rem,env(safe-area-inset-bottom))]" aria-label="<?php esc_attr_e('Solliciteer voor deze vacature', 'boozed'); ?>">
	<div class="vacature-apply-banner__title-wrap overflow-hidden md:overflow-visible md:min-w-0 md:max-w-[50%] shrink-0">
		<div class="vacature-apply-banner__title-inner flex whitespace-nowrap will-change-transform md:contents">
			<span class="vacature-apply-banner__title font-heading font-bold text-body-md md:text-h4 md:text-h4-lg text-white inline-block pr-[1em] md:pr-0 md:truncate md:min-w-0">
				<?php echo esc_html($title); ?>
			</span>
			<span class="vacature-apply-banner__title-dupe font-heading font-bold text-body-md text-white inline-block pr-[1em] hidden md:!hidden" aria-hidden="true">
				<?php echo esc_html($title); ?>
			</span>
		</div>
	</div>
	<a href="<?php echo esc_url($apply_url); ?>" class="vacature-apply-banner__btn inline-flex items-center justify-center gap-2 shrink-0 w-full md:w-auto min-h-[48px] bg-[#EF4242] hover:bg-[#e63939] text-white font-body font-medium text-body-sm md:text-body-md py-3 px-6 rounded transition-colors no-underline">
		<?php esc_html_e('Solliciteer voor deze functie', 'boozed'); ?>
		<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
	</a>
</aside>
<?php if (empty($GLOBALS['boozed_vacature_apply_banner_ticker_printed'])) : $GLOBALS['boozed_vacature_apply_banner_ticker_printed'] = true; ?>
<script>
(function() {
	function runVacatureBannerTicker() {
		var wrap = document.querySelector('.vacature-apply-banner__title-wrap');
		var inner = document.querySelector('.vacature-apply-banner__title-inner');
		var dupe = document.querySelector('.vacature-apply-banner__title-dupe');
		if (!wrap || !inner) return;
		var isMobile = window.innerWidth < 768;
		if (isMobile) {
			if (dupe) dupe.classList.remove('hidden');
			var wrapWidth = wrap.offsetWidth;
			var innerWidth = inner.scrollWidth;
			var shouldTick = innerWidth > wrapWidth;
			inner.classList.toggle('vacature-apply-banner__title-inner--ticker', shouldTick);
			if (dupe) dupe.classList.toggle('hidden', !shouldTick);
		} else {
			inner.classList.remove('vacature-apply-banner__title-inner--ticker');
			if (dupe) dupe.classList.add('hidden');
		}
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			runVacatureBannerTicker();
			if (document.fonts && document.fonts.ready) document.fonts.ready.then(runVacatureBannerTicker);
			setTimeout(runVacatureBannerTicker, 100);
		});
	} else {
		runVacatureBannerTicker();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(runVacatureBannerTicker);
		setTimeout(runVacatureBannerTicker, 100);
	}
	window.addEventListener('resize', runVacatureBannerTicker);
})();
</script>
<?php endif; ?>
