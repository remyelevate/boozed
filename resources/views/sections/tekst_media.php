<?php

/**
 * Text + Media section
 * Toggle position left/right, large caption with optional ticker, image or video (sound on/off, start muted), optional buttons.
 */

$caption           = function_exists('get_sub_field') ? (string) get_sub_field('tekst_media_caption') : '';
$position          = function_exists('get_sub_field') ? get_sub_field('tekst_media_position') : 'left';
$remove_top_padding = function_exists('get_sub_field') ? (bool) get_sub_field('tekst_media_remove_top_padding') : false;
$media_type        = function_exists('get_sub_field') ? get_sub_field('tekst_media_type') : 'image';
$image_id          = function_exists('get_sub_field') ? get_sub_field('tekst_media_image') : null;
$video_url         = function_exists('get_sub_field') ? get_sub_field('tekst_media_video') : '';
$content           = function_exists('get_sub_field') ? get_sub_field('tekst_media_content') : '';
$primary_label     = function_exists('get_sub_field') ? (string) get_sub_field('tekst_media_primary_label') : '';
$primary_url       = function_exists('get_sub_field') ? (string) get_sub_field('tekst_media_primary_url') : '';
$secondary_label   = function_exists('get_sub_field') ? (string) get_sub_field('tekst_media_secondary_label') : '';
$secondary_url     = function_exists('get_sub_field') ? (string) get_sub_field('tekst_media_secondary_url') : '';

$is_media_left     = ($position === 'left');
$use_video         = ($media_type === 'video' && $video_url);
$image_url         = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$has_media         = $use_video || $image_url;
$show_primary_btn  = $primary_url !== '' && $primary_label !== '';
$show_secondary_btn = $secondary_url !== '' && $secondary_label !== '';

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';

// Phosphor speaker icons (regular weight)
$phosphor_speaker_high = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M155.51 24.81a8 8 0 0 0-8.42.88L77.25 80H32a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h45.25l69.84 54.31A8 8 0 0 0 160 224V32a8 8 0 0 0-4.49-7.19ZM144 207.64L84.91 161.69A7.94 7.94 0 0 0 80 160H32V96h48a7.94 7.94 0 0 0 4.91-1.69L144 48.36Zm54-106.08a40 40 0 0 1 0 52.88a8 8 0 0 1-12-10.58a24 24 0 0 0 0-31.72a8 8 0 0 1 12-10.58Zm32-56.56a8 8 0 1 1-12 10.58a104 104 0 0 0 0 144.84a8 8 0 1 1-12 10.58a120 120 0 0 1 0-166Z"/></svg>';
$phosphor_speaker_slash = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M53.92 34.62a8 8 0 1 0-11.84 10.76L73.55 80H32a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h45.25l69.84 54.31A8 8 0 0 0 160 224v-60.07l42.08 46.29a8 8 0 1 0 11.84-10.76ZM144 207.64L84.91 161.69A7.94 7.94 0 0 0 80 160H32V96h48a7.94 7.94 0 0 0 4.91-1.69l9.26-7.21L144 141.27Zm-3.91-127.95a8 8 0 0 1 .91-11.27l6.09-4.73L144 48.36v54.12a8 8 0 0 1-16 0V32a8 8 0 0 1 12.91-6.31l20.1 15.64a8 8 0 0 1-10.36 12.21Z"/></svg>';
?>
<section class="tekst-media w-full relative overflow-y-visible <?php echo $is_media_left ? '' : 'tekst-media--media-right'; ?><?php echo $remove_top_padding ? ' tekst-media--no-top-padding' : ''; ?>">
	<div class="tekst-media__inner max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y overflow-x-clip">
		<div class="tekst-media__grid grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-center">
			<div class="tekst-media__media overflow-visible <?php echo $is_media_left ? 'md:order-1' : 'md:order-2'; ?> <?php echo $is_media_left ? 'order-2' : 'order-1'; ?>">
				<?php if ($has_media) : ?>
					<div class="tekst-media__media-inner relative w-full overflow-visible">
						<?php if ($use_video) : ?>
							<div class="tekst-media__video-wrap tekst-media__media-reveal relative aspect-[535/688] bg-brand-black overflow-hidden">
								<video class="tekst-media__video w-full h-full object-cover" autoplay muted loop playsinline>
									<source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
								</video>
							</div>
							<button type="button" class="tekst-media__sound flex items-center gap-2 text-brand-indigo text-body-sm font-body mt-3 hover:opacity-70 transition-opacity" aria-label="<?php esc_attr_e('Sound on', 'boozed'); ?>">
								<span class="tekst-media__sound-icon--on"><?php echo $phosphor_speaker_slash; ?></span>
								<span class="tekst-media__sound-icon--off hidden"><?php echo $phosphor_speaker_high; ?></span>
								<span class="tekst-media__sound-label underline"><?php esc_html_e('Sound on', 'boozed'); ?></span>
							</button>
						<?php else : ?>
							<div class="tekst-media__image-wrap tekst-media__media-reveal relative aspect-[535/688] overflow-hidden bg-brand-border">
								<img src="<?php echo esc_url($image_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="tekst-media__content relative z-10 <?php echo $is_media_left ? 'md:order-2' : 'md:order-1'; ?> <?php echo $is_media_left ? 'order-1' : 'order-2'; ?>">
				<?php if ($content) : ?>
					<div class="tekst-media__body prose prose-lg font-body text-body-md text-brand-black max-w-none mb-6">
						<?php echo wp_kses_post(wpautop($content)); ?>
					</div>
				<?php endif; ?>
				<?php if ($show_primary_btn || $show_secondary_btn) : ?>
					<div class="tekst-media__actions flex flex-wrap items-center gap-4 md:gap-6">
						<?php if ($show_primary_btn) : ?>
							<?php
							\App\Components::render('button', [
								'variant'          => 'coral',
								'label'            => $primary_label,
								'href'             => $primary_url,
								'icon_right_html'  => $phosphor_chevron_right,
								'class'            => '!bg-brand-coral',
							]);
							?>
						<?php endif; ?>
						<?php if ($show_secondary_btn) : ?>
							<a href="<?php echo esc_url($secondary_url); ?>" class="tekst-media__cta-secondary font-body text-body-md font-medium text-brand-black no-underline relative inline-block hover:opacity-90 transition-opacity">
								<?php echo esc_html($secondary_label); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if ($caption !== '' && $has_media) : ?>
		<div class="tekst-media__caption-outer overflow-x-hidden overflow-y-visible pt-4 pointer-events-none" aria-hidden="true" data-tekst-media-caption>
			<div class="tekst-media__caption-wrap w-full min-w-full overflow-x-hidden overflow-y-visible">
				<div class="tekst-media__caption-inner flex whitespace-nowrap will-change-transform pl-4 md:pl-section-x">
					<span class="tekst-media__caption font-heading font-bold text-[56px] md:text-[124px] text-brand-indigo inline-block pr-[1em]">
						<?php echo esc_html($caption); ?>
					</span>
					<span class="tekst-media__caption-dupe font-heading font-bold text-[56px] md:text-[124px] text-brand-indigo inline-block pr-[1em]" aria-hidden="true">
						<?php echo esc_html($caption); ?>
					</span>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if (empty($GLOBALS['boozed_tekst_media_script_printed'])) : $GLOBALS['boozed_tekst_media_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		/* Media: reveal from bottom using IntersectionObserver + CSS transition */
		var revealElements = document.querySelectorAll('.tekst-media__media-reveal');
		if (revealElements.length && 'IntersectionObserver' in window) {
			var observer = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-revealed');
						observer.unobserve(entry.target);
					}
				});
			}, { threshold: 0, rootMargin: '0px 0px 300px 0px' });
			revealElements.forEach(function(el) {
				observer.observe(el);
			});
			/* Fallback: reveal after 2s if still hidden (in case observer doesn't fire) */
			setTimeout(function() {
				revealElements.forEach(function(el) {
					if (!el.classList.contains('is-revealed')) {
						el.classList.add('is-revealed');
					}
				});
			}, 2000);
		} else {
			revealElements.forEach(function(el) {
				el.classList.add('is-revealed');
			});
		}

		/* Caption ticker: enable marquee when caption is wider than visible area (wrap or viewport) */
		function runCaptionTicker() {
			document.querySelectorAll('.tekst-media__caption-wrap').forEach(function(wrap) {
				var inner = wrap.querySelector('.tekst-media__caption-inner');
				if (!inner) return;
				var wrapWidth = wrap.offsetWidth;
				var innerWidth = inner.scrollWidth;
				var viewportWidth = window.innerWidth;
				var visibleWidth = Math.min(wrapWidth, viewportWidth);
				inner.classList.toggle('tekst-media__caption-inner--ticker', innerWidth > visibleWidth);
			});
		}
		runCaptionTicker();
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(runCaptionTicker);
		}
		setTimeout(runCaptionTicker, 100);
		window.addEventListener('resize', runCaptionTicker);

		/* Fixed caption: position viewport-wide caption so it aligns with section (independent of container) */
		function positionFixedCaptions() {
			var offsetBottom = window.innerWidth >= 768 ? 120 : 100;
			document.querySelectorAll('.tekst-media').forEach(function(section) {
				var caption = section.querySelector('[data-tekst-media-caption]');
				if (!caption) return;
				var sectionRect = section.getBoundingClientRect();
				var vh = window.innerHeight;
				if (sectionRect.bottom < 0 || sectionRect.top > vh) {
					caption.style.visibility = 'hidden';
					return;
				}
				caption.style.visibility = 'visible';
				var top = sectionRect.bottom - offsetBottom - caption.offsetHeight;
				caption.style.top = top + 'px';
			});
		}
		var captionTick;
		function schedulePositionCaptions() {
			if (captionTick) return;
			captionTick = requestAnimationFrame(function() {
				captionTick = 0;
				positionFixedCaptions();
			});
		}
		positionFixedCaptions();
		window.addEventListener('scroll', schedulePositionCaptions, { passive: true });
		window.addEventListener('resize', function() {
			schedulePositionCaptions();
			runCaptionTicker();
		});

		/* Sound toggle for video */
		var soundOn = '<?php echo esc_js(__('Sound on', 'boozed')); ?>';
		var soundOff = '<?php echo esc_js(__('Sound off', 'boozed')); ?>';
		document.querySelectorAll('.tekst-media').forEach(function(section) {
			var video = section.querySelector('.tekst-media__video');
			var soundBtn = section.querySelector('.tekst-media__sound');
			if (!video || !soundBtn) return;
			var label = section.querySelector('.tekst-media__sound-label');
			var iconOn = section.querySelector('.tekst-media__sound-icon--on');
			var iconOff = section.querySelector('.tekst-media__sound-icon--off');
			soundBtn.addEventListener('click', function() {
				video.muted = !video.muted;
				if (video.muted) {
					if (label) label.textContent = soundOn;
					soundBtn.setAttribute('aria-label', soundOn);
					if (iconOn) iconOn.classList.remove('hidden');
					if (iconOff) iconOff.classList.add('hidden');
				} else {
					if (label) label.textContent = soundOff;
					soundBtn.setAttribute('aria-label', soundOff);
					if (iconOn) iconOn.classList.add('hidden');
					if (iconOff) iconOff.classList.remove('hidden');
				}
			});
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
<?php endif; ?>
