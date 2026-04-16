<?php
/**
 * Vacancy application modal — opens from same-page links to #solliciteren.
 * Custom overlay (not <dialog>) so backdrop is a real element and always disappears on close.
 */

if (!is_singular('vacature')) {
    return;
}

$job_title = get_the_title();

$cf7_shortcode = '';
if (function_exists('get_field')) {
    $from_acf = trim((string) get_field('vacature_sollicitatie_cf7_shortcode', 'option'));
    if ($from_acf !== '') {
        $cf7_shortcode = $from_acf;
    }
}
if ($cf7_shortcode === '' && class_exists(\App\ContactForm7VacatureSollicitatie::class)) {
    $cf7_shortcode = \App\ContactForm7VacatureSollicitatie::get_shortcode();
}

$has_cf7 = $cf7_shortcode !== '' && class_exists('WPCF7_ContactForm');
?>
<div id="vacature-sollicitatie-modal" class="vacature-sollicitatie-modal" aria-hidden="true">
	<div class="vacature-sollicitatie-modal__backdrop" data-vacature-sollicitatie-close tabindex="-1" aria-hidden="true"></div>
	<div class="vacature-sollicitatie-modal__panel bg-brand-white text-brand-indigo shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="vacature-sollicitatie-title" data-lenis-prevent>
		<div class="vacature-sollicitatie-modal__shell flex min-h-0 flex-col">
			<header class="flex shrink-0 items-start justify-between gap-4 border-b border-brand-border px-6 py-5 md:px-8 md:py-6">
				<h2 id="vacature-sollicitatie-title" class="font-heading text-h4 md:text-h3-lg font-bold leading-tight text-brand-indigo pr-2">
					<?php esc_html_e('Ik wil solliciteren!', 'boozed'); ?>
				</h2>
				<button type="button" class="shrink-0 rounded p-2 text-brand-indigo/60 transition-colors hover:bg-brand-nude/30 hover:text-brand-indigo focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2" data-vacature-sollicitatie-close aria-label="<?php esc_attr_e('Sluiten', 'boozed'); ?>">
					<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 18L18 6M6 6l12 12"/></svg>
				</button>
			</header>

			<div class="vacature-sollicitatie-modal__scroll min-h-0 flex-1 overflow-y-auto overflow-x-hidden overscroll-contain px-6 py-5 md:px-8 md:py-6" data-lenis-prevent>
				<p class="font-body text-body-md text-brand-indigo mb-6 leading-relaxed">
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: job title */
							__('Wat leuk dat je wilt solliciteren op de functie <strong class="text-brand-purple font-semibold">%s</strong>.', 'boozed'),
							esc_html($job_title)
						),
						[
							'strong' => [
								'class' => true,
							],
						]
					);
					?>
				</p>
				<p class="font-body text-body-sm text-brand-indigo/90 mb-8 leading-relaxed">
					<?php esc_html_e('Hiervoor willen we je vragen onderstaand formulier in te vullen. Wij komen vervolgens zo spoedig mogelijk met een reactie op je sollicitatie.', 'boozed'); ?>
				</p>

				<?php if ($has_cf7) : ?>
					<div class="vacature-sollicitatie-modal__cf7">
						<?php echo do_shortcode($cf7_shortcode); ?>
					</div>
				<?php elseif (current_user_can('edit_theme_options')) : ?>
					<p class="font-body text-body-sm text-red-600">
						<?php
						if (class_exists('WPCF7_ContactForm')) {
							echo esc_html(
								__('Vacature sollicitatie: geen shortcode. Vul “Sollicitatieformulier (shortcode)” onder Global Settings → Vacatures, of laat het leeg en gebruik het formulier “Boozed Vacature sollicitatie” (zie VACATURE-SOLLICITATIE-CF7-MARKUP.md).', 'boozed')
							);
						} else {
							echo esc_html(__('Contact Form 7 is niet actief. Installeer/activeer CF7 om het sollicitatieformulier te tonen.', 'boozed'));
						}
						?>
					</p>
				<?php endif; ?>
			</div>

			<footer class="flex shrink-0 justify-end border-t border-brand-border px-6 py-4 md:px-8">
				<button type="button" class="inline-flex min-h-[48px] items-center justify-center border-2 border-brand-purple bg-brand-white px-6 py-3 font-body text-body font-medium text-brand-purple transition-colors hover:bg-brand-nude focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2" data-vacature-sollicitatie-close>
					<?php esc_html_e('Sluiten', 'boozed'); ?>
				</button>
			</footer>
		</div>
	</div>
</div>
