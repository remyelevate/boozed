<?php

/**
 * Marquee section
 * Large caption only, white background, dark blue text. Uses same ticker logic as page header:
 * when caption is wider than viewport, it scrolls horizontally with a duplicated strip.
 *
 * When $marquee_caption_override is set (e.g. from single post template), that value is used
 * instead of the ACF sub_field.
 */

$caption = isset($marquee_caption_override) && $marquee_caption_override !== ''
	? (string) $marquee_caption_override
	: (function_exists('get_sub_field') ? (string) get_sub_field('marquee_caption') : '');
?>
<?php
$marquee_extra_top = isset($marquee_extra_top) && $marquee_extra_top;
?>
<?php if ($caption !== '') : ?>
<section class="marquee bg-brand-white overflow-x-hidden overflow-y-visible py-6 md:py-8 <?php echo $marquee_extra_top ? 'md:pt-20' : ''; ?>">
	<div class="marquee__caption-wrap w-full min-w-[100vw] overflow-x-hidden overflow-y-visible pointer-events-none">
		<div class="marquee__caption-inner flex whitespace-nowrap will-change-transform pl-4 md:pl-section-x" style="margin-left: max(0px, calc((100% - 1920px) / 2));">
			<p class="marquee__caption font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] text-brand-indigo inline-block pr-[1em]">
				<?php echo esc_html($caption); ?>
			</p>
			<span class="marquee__caption-dupe font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] text-brand-indigo inline-block pr-[1em] hidden" aria-hidden="true">
				<?php echo esc_html($caption); ?>
			</span>
		</div>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_marquee_ticker_script_printed'])) : $GLOBALS['boozed_marquee_ticker_script_printed'] = true; ?>
<script>
(function() {
	function runMarqueeTicker() {
		document.querySelectorAll('.marquee__caption-wrap').forEach(function(wrap) {
			var inner = wrap.querySelector('.marquee__caption-inner');
			var dupe = wrap.querySelector('.marquee__caption-dupe');
			if (!inner) return;
			if (dupe) dupe.classList.remove('hidden');
			var innerWidth = inner.scrollWidth;
			var viewportWidth = window.innerWidth;
			var shouldTick = innerWidth > viewportWidth;
			inner.classList.toggle('marquee__caption-inner--ticker', shouldTick);
			if (dupe) dupe.classList.toggle('hidden', !shouldTick);
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			runMarqueeTicker();
			if (document.fonts && document.fonts.ready) document.fonts.ready.then(runMarqueeTicker);
			setTimeout(runMarqueeTicker, 100);
		});
	} else {
		runMarqueeTicker();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(runMarqueeTicker);
		setTimeout(runMarqueeTicker, 100);
	}
	window.addEventListener('resize', runMarqueeTicker);
})();
</script>
<?php endif; ?>
<?php endif; ?>
