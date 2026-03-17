<?php

/**
 * Offerte aanvraag section
 * Multi-step quote request form with progress bar and step images.
 * Design system: max-w-section, brand-purple headings, coral CTA, custom form + GSAP animations.
 */

$intro_text         = function_exists('get_sub_field') ? get_sub_field('offerte_intro_text') : '';
$step1_title        = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step1_title') : '';
$step1_description  = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step1_description') : '';
$step1_cta_label    = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step1_cta_label') : '';
$help_text          = function_exists('get_sub_field') ? (string) get_sub_field('offerte_help_text') : '';
$image_ids          = [
    1 => function_exists('get_sub_field') ? get_sub_field('offerte_image_step1') : null,
    2 => function_exists('get_sub_field') ? get_sub_field('offerte_image_step2') : null,
    3 => function_exists('get_sub_field') ? get_sub_field('offerte_image_step3') : null,
    4 => function_exists('get_sub_field') ? get_sub_field('offerte_image_step4') : null,
];

$step_labels = [
    1 => function_exists('get_sub_field') ? (string) get_sub_field('offerte_step1_label') : '',
    2 => function_exists('get_sub_field') ? (string) get_sub_field('offerte_step2_label') : '',
    3 => function_exists('get_sub_field') ? (string) get_sub_field('offerte_step3_label') : '',
    4 => function_exists('get_sub_field') ? (string) get_sub_field('offerte_step4_label') : '',
];
$step_labels = [
    1 => $step_labels[1] ?: 'Start je aanvraag',
    2 => $step_labels[2] ?: 'Persoonlijke gegevens',
    3 => $step_labels[3] ?: 'Praktische informatie',
    4 => $step_labels[4] ?: 'Je experience',
];

$step1_title       = $step1_title ?: 'Aanvraag starten';
$step1_cta_label   = $step1_cta_label ?: 'Begin jouw aanvraag >';
$help_text         = $help_text ?: 'Heb je een andere vraag en ben je niet geholpen met dit formulier? Stuur dan gemakkelijk een mailtje naar info@boozed.nl';

$step2_title    = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step2_title') : '';
$step2_subtitle = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step2_subtitle') : '';
$step3_title    = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step3_title') : '';
$step3_subtitle = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step3_subtitle') : '';
$step4_title    = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step4_title') : '';
$step4_subtitle = function_exists('get_sub_field') ? (string) get_sub_field('offerte_step4_subtitle') : '';
$step2_title    = $step2_title ?: 'Persoonlijke gegevens';
$step2_subtitle = $step2_subtitle ?: 'Vertel ons wat meer over jezelf.';
$step3_title    = $step3_title ?: 'Praktische informatie';
$step3_subtitle = $step3_subtitle ?: 'Vertel ons meer over je aanvraag.';
$step4_title    = $step4_title ?: 'Over je experience';
$step4_subtitle = $step4_subtitle ?: 'Om wat voor event/experience gaat het? Heb je een specifiek thema in gedachten of een lijstje met materialen die je nodig hebt? Vul dit dan hier in!';

$theme_dir = get_template_directory();
$theme_uri = get_template_directory_uri();
if (file_exists($theme_dir . '/assets/js/offerte-aanvraag.js')) {
    wp_enqueue_script(
        'boozed-offerte-aanvraag',
        $theme_uri . '/assets/js/offerte-aanvraag.js',
        ['gsap'],
        filemtime($theme_dir . '/assets/js/offerte-aanvraag.js'),
        true
    );
}

$recipient_email = function_exists('get_sub_field') ? sanitize_email((string) get_sub_field('offerte_recipient_email')) : '';
$form_action = admin_url('admin-ajax.php');
$nonce       = wp_create_nonce('boozed_offerte_aanvraag');
?>
<section class="section-offerte-aanvraag bg-brand-white overflow-hidden" data-offerte-nonce="<?php echo esc_attr($nonce); ?>" data-offerte-ajax="<?php echo esc_url($form_action); ?>">
	<div class="section-offerte-aanvraag__inner max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
		<!-- Progress: step indicators + bar -->
		<nav class="section-offerte-aanvraag__progress mb-8 md:mb-12" aria-label="<?php esc_attr_e('Formulier stappen', 'boozed'); ?>">
			<ol class="flex items-stretch gap-2 md:gap-4 mb-3" role="list">
				<?php foreach ([1, 2, 3, 4] as $i) : ?>
					<li class="section-offerte-aanvraag__step flex min-w-0 flex-1 items-center" data-step="<?php echo (int) $i; ?>">
						<div class="flex items-center gap-2 md:gap-3 shrink-0 min-w-0">
							<span class="section-offerte-aanvraag__step-circle flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 font-heading font-bold text-h4 leading-none transition-colors duration-300 <?php echo $i === 1 ? 'bg-brand-purple border-brand-purple text-brand-white' : 'border-brand-border bg-brand-white text-gray-400'; ?>" aria-current="<?php echo $i === 1 ? 'step' : 'false'; ?>"><?php echo (int) $i; ?></span>
							<span class="section-offerte-aanvraag__step-label font-body text-body-lg font-medium <?php echo $i === 1 ? 'text-brand-purple' : 'text-gray-400'; ?>"><?php echo esc_html($step_labels[ $i ]); ?></span>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
			<!-- Progress bar: 4 segments aligned with steps, each segment fills with animation -->
			<div class="section-offerte-aanvraag__bar-track flex w-full gap-0.5 md:gap-1" style="height: 10px;" aria-hidden="true">
				<?php foreach ([1, 2, 3, 4] as $i) : ?>
					<div class="section-offerte-aanvraag__bar-segment flex-1 min-w-0 overflow-hidden rounded-full bg-brand-progress-track" data-bar-segment="<?php echo (int) $i; ?>">
						<div class="section-offerte-aanvraag__bar-segment-fill h-full w-full origin-left rounded-full bg-brand-indigo" style="transform: scaleX(0);"></div>
					</div>
				<?php endforeach; ?>
			</div>
		</nav>

		<div class="section-offerte-aanvraag__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
			<!-- Left: form content -->
			<div class="section-offerte-aanvraag__form-col order-1">
				<form class="section-offerte-aanvraag__form" action="<?php echo esc_url($form_action); ?>" method="post" enctype="multipart/form-data" novalidate>
					<input type="hidden" name="action" value="boozed_offerte_aanvraag_submit">
					<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
					<?php if ($recipient_email) : ?>
						<input type="hidden" name="recipient_email" value="<?php echo esc_attr($recipient_email); ?>">
					<?php endif; ?>

					<!-- Step 1: Start -->
					<div class="section-offerte-aanvraag__panel overflow-hidden" data-panel="1">
						<?php if ($intro_text) : ?>
							<div class="section-offerte-aanvraag__intro font-body text-body-md text-brand-indigo mb-6 prose prose-p:mb-4">
								<?php echo wp_kses_post($intro_text); ?>
							</div>
						<?php endif; ?>
						<h2 class="section-offerte-aanvraag__title font-heading font-bold text-h4 md:text-h3-lg text-brand-purple mt-0 mb-4"><?php echo esc_html($step1_title); ?></h2>
						<?php if ($step1_description) : ?>
							<p class="section-offerte-aanvraag__desc font-body text-body-md text-gray-600 mb-6"><?php echo esc_html($step1_description); ?></p>
						<?php endif; ?>
						<button type="button" class="section-offerte-aanvraag__next js-offerte-next !bg-brand-coral text-brand-white px-5 py-2.5 font-body text-body font-medium inline-flex items-center gap-2">
							<?php echo esc_html($step1_cta_label); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>
						</button>
					</div>

					<!-- Step 2: Persoonlijke gegevens -->
					<div class="section-offerte-aanvraag__panel hidden overflow-hidden" data-panel="2">
						<h2 class="section-offerte-aanvraag__title font-heading font-bold text-h4 md:text-h3-lg text-brand-purple mt-0 mb-2"><?php echo esc_html($step2_title); ?></h2>
						<p class="section-offerte-aanvraag__desc font-body text-body-md text-gray-600 mb-6"><?php echo esc_html($step2_subtitle); ?></p>
						<div class="space-y-4">
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
								<?php \App\Components::render('input', ['name' => 'voornaam', 'placeholder' => __('Voornaam', 'boozed'), 'required' => true]); ?>
								<?php \App\Components::render('input', ['name' => 'achternaam', 'placeholder' => __('Achternaam', 'boozed'), 'required' => true]); ?>
							</div>
							<?php \App\Components::render('input', ['name' => 'bedrijfsnaam', 'placeholder' => __('Bedrijfsnaam', 'boozed')]); ?>
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
								<?php \App\Components::render('input', ['name' => 'email', 'type' => 'email', 'placeholder' => __('Email adres', 'boozed'), 'required' => true]); ?>
								<?php \App\Components::render('input', ['name' => 'telefoon', 'type' => 'text', 'placeholder' => __('Telefoonnummer', 'boozed')]); ?>
							</div>
							<?php \App\Components::render('input', ['name' => 'adres', 'placeholder' => __('Adres', 'boozed')]); ?>
						</div>
						<div class="flex flex-wrap gap-3 mt-6">
							<button type="button" class="section-offerte-aanvraag__back js-offerte-back bg-brand-white text-brand-purple border-2 border-brand-purple hover:bg-brand-nude px-5 py-2.5 font-body text-body font-medium">Terug</button>
							<button type="button" class="section-offerte-aanvraag__next js-offerte-next !bg-brand-coral text-brand-white px-5 py-2.5 font-body text-body font-medium inline-flex items-center gap-2">Volgende stap &gt;</button>
						</div>
					</div>

					<!-- Step 3: Praktische informatie -->
					<div class="section-offerte-aanvraag__panel hidden overflow-hidden" data-panel="3">
						<h2 class="section-offerte-aanvraag__title font-heading font-bold text-h4 md:text-h3-lg text-brand-purple mt-0 mb-2"><?php echo esc_html($step3_title); ?></h2>
						<p class="section-offerte-aanvraag__desc font-body text-body-md text-gray-600 mb-6"><?php echo esc_html($step3_subtitle); ?></p>
						<div class="space-y-4">
							<?php \App\Components::render('input', ['name' => 'locatie', 'placeholder' => __('Op welke locatie is het?', 'boozed')]); ?>
							<?php \App\Components::render('input', ['name' => 'datum_nodig', 'placeholder' => __('Op welke datum heb je het nodig?', 'boozed')]); ?>
							<?php \App\Components::render('input', ['name' => 'opbouw', 'placeholder' => __('Wanneer kunnen we het opbouwen?', 'boozed')]); ?>
							<?php \App\Components::render('input', ['name' => 'afbouw', 'placeholder' => __('Wanneer kunnen we afbouwen?', 'boozed')]); ?>
						</div>
						<div class="flex flex-wrap gap-3 mt-6">
							<button type="button" class="section-offerte-aanvraag__back js-offerte-back bg-brand-white text-brand-purple border-2 border-brand-purple hover:bg-brand-nude px-5 py-2.5 font-body text-body font-medium">Terug</button>
							<button type="button" class="section-offerte-aanvraag__next js-offerte-next !bg-brand-coral text-brand-white px-5 py-2.5 font-body text-body font-medium inline-flex items-center gap-2">Volgende stap &gt;</button>
						</div>
					</div>

					<!-- Step 4: Je experience -->
					<div class="section-offerte-aanvraag__panel hidden overflow-hidden" data-panel="4">
						<h2 class="section-offerte-aanvraag__title font-heading font-bold text-h4 md:text-h3-lg text-brand-purple mt-0 mb-2"><?php echo esc_html($step4_title); ?></h2>
						<p class="section-offerte-aanvraag__desc font-body text-body-md text-gray-600 mb-6"><?php echo esc_html($step4_subtitle); ?></p>
						<div class="space-y-4">
							<?php \App\Components::render('textarea', ['name' => 'experience', 'placeholder' => __('Omschrijf je experience aan ons', 'boozed'), 'rows' => 5]); ?>
							<div class="section-offerte-aanvraag__file-wrap">
								<label class="flex rounded border border-brand-border border-dashed bg-brand-white p-6 cursor-pointer hover:border-brand-purple/50 transition-colors">
									<input type="file" name="offerte_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.webp" class="sr-only js-offerte-file-input">
									<span class="flex items-center gap-3 text-gray-500 font-body text-body">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256" fill="currentColor" class="shrink-0 text-gray-400" aria-hidden="true"><path d="M224 152v56a16 16 0 0 1-16 16H48a16 16 0 0 1-16-16v-56a8 8 0 0 1 16 0v56h128v-56a8 8 0 0 1 16 0Zm-96-96a8 8 0 0 0-8 8v76.69l-18.34-18.35a8 8 0 0 0-11.32 11.32l32 32a8 8 0 0 0 11.32 0l32-32a8 8 0 0 0-11.32-11.32L136 140.69V64a8 8 0 0 0-8-8Z"/></svg>
										<span class="js-offerte-file-label">Klik of sleep je bestanden naar dit veld</span>
									</span>
								</label>
							</div>
						</div>
						<div class="flex flex-wrap gap-3 mt-6">
							<button type="button" class="section-offerte-aanvraag__back js-offerte-back bg-brand-white text-brand-purple border-2 border-brand-purple hover:bg-brand-nude px-5 py-2.5 font-body text-body font-medium">Terug</button>
							<button type="submit" class="section-offerte-aanvraag__submit js-offerte-submit !bg-brand-coral text-brand-white px-5 py-2.5 font-body text-body font-medium inline-flex items-center gap-2">Versturen &gt;</button>
						</div>
					</div>

					<div class="section-offerte-aanvraag__message hidden mt-6 font-body text-body-md" role="alert"></div>
				</form>
			</div>

			<!-- Right: step image + info box -->
			<div class="section-offerte-aanvraag__visual-col order-2 space-y-4">
				<?php foreach ([1, 2, 3, 4] as $i) : ?>
					<?php
					$img_id = $image_ids[ $i ] ?? null;
					$img_url = $img_id ? wp_get_attachment_image_url($img_id, 'large') : '';
					?>
					<div class="section-offerte-aanvraag__image-wrap <?php echo $i === 1 ? '' : 'hidden'; ?> overflow-hidden rounded-lg bg-brand-border aspect-[4/3]" data-image-step="<?php echo (int) $i; ?>">
						<?php if ($img_url) : ?>
							<img src="<?php echo esc_url($img_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
						<?php else : ?>
							<div class="w-full h-full flex items-center justify-center text-gray-300 font-body text-body-sm"><?php echo (int) $i; ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
				<div class="section-offerte-aanvraag__help bg-brand-nude border border-brand-border rounded-lg p-4 font-body text-body text-gray-700">
					<?php
					echo wp_kses(
						preg_replace(
							'#\b(info@boozed\.nl)\b#i',
							'<a href="mailto:info@boozed.nl" class="text-brand-purple font-medium underline">$1</a>',
							$help_text
						),
						['a' => ['href' => [], 'class' => []]]
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
