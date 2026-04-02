<?php
/**
 * 404 page header – same structure as page_header section (content + CTAs, light).
 * Expects: $title, $content, $primary_label, $primary_url, $secondary_label, $secondary_url
 */
$background   = 'light';
$is_dark      = false;
$bg_class     = 'bg-brand-white';
$title_color  = 'text-brand-indigo';
$text_color   = 'text-brand-black';
$show_primary = ! empty( $primary_url ) && ! empty( $primary_label );
$show_secondary = ! empty( $secondary_url ) && ! empty( $secondary_label );

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
?>
<section class="page-header <?php echo esc_attr( $bg_class ); ?> pt-24 min-h-0 md:pt-[50px] flex flex-col justify-start overflow-x-hidden" data-page-header-background="<?php echo esc_attr( $background ); ?>">
	<?php if ( $title !== '' ) : ?>
		<div class="page-header__title-wrap w-full min-w-[100vw] overflow-x-hidden overflow-y-visible pointer-events-none mb-4 md:mb-6">
			<div class="page-header__title-inner flex whitespace-nowrap will-change-transform pl-4 md:pl-section-x" style="margin-left: max(0px, calc((100% - 1920px) / 2));">
				<h1 class="page-header__title font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] <?php echo esc_attr( $title_color ); ?> inline-block pr-[1em]">
					<?php echo esc_html( $title ); ?>
				</h1>
				<span class="page-header__title-dupe font-heading font-bold text-[40px] leading-tight md:text-[124px] md:leading-[1.1] <?php echo esc_attr( $title_color ); ?> inline-block pr-[1em] hidden" aria-hidden="true">
					<?php echo esc_html( $title ); ?>
				</span>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $content || $show_primary || $show_secondary ) : ?>
		<div class="page-header__bottom max-w-section mx-auto w-full px-4 pb-10 md:px-section-x md:pb-section-y">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 md:items-end">
				<?php if ( $content ) : ?>
					<div class="page-header__body prose prose-lg font-body text-body-md <?php echo esc_attr( $text_color ); ?> max-w-none md:pr-[25%]">
						<?php echo wp_kses_post( wpautop( $content ) ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $show_primary || $show_secondary ) : ?>
					<div class="page-header__actions flex flex-wrap items-center gap-4 md:gap-6 <?php echo $content ? '' : 'md:col-start-2'; ?>">
						<?php if ( $show_primary ) : ?>
							<?php
							\App\Components::render( 'button', [
								'variant'          => 'coral',
								'label'            => $primary_label,
								'href'             => $primary_url,
								'icon_right_html'  => $phosphor_chevron_right,
								'class'            => '!bg-brand-coral',
							] );
							?>
						<?php endif; ?>
						<?php if ( $show_secondary ) : ?>
							<a href="<?php echo esc_url( $secondary_url ); ?>" class="page-header__cta-secondary font-body text-body-md font-medium text-brand-indigo no-underline relative inline-block hover:opacity-90 transition-opacity">
								<?php echo esc_html( $secondary_label ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if ( $title !== '' && empty( $GLOBALS['boozed_page_header_ticker_script_printed'] ) ) : $GLOBALS['boozed_page_header_ticker_script_printed'] = true; ?>
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
