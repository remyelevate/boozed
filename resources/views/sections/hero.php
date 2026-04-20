<?php

/**
 * Hero section
 * Backdrop: video or image. Title and primary/secondary CTAs via ACF.
 * Bottom cards: Experience, Rental, Fabrications (1/3 each) with custom cursor.
 */

$backdrop_type   = function_exists('get_sub_field') ? get_sub_field('hero_backdrop_type') : 'image';
$image_id        = function_exists('get_sub_field') ? get_sub_field('hero_background_image') : null;
$video_url       = function_exists('get_sub_field') ? get_sub_field('hero_background_video') : '';
$heading         = function_exists('get_sub_field') ? get_sub_field('hero_heading') : 'Wij maken beleving tastbaar.';
$primary_label   = function_exists('get_sub_field') ? get_sub_field('hero_primary_button_text') : '';
$primary_url     = function_exists('get_sub_field') ? get_sub_field('hero_primary_button_url') : '';
$secondary_label = function_exists('get_sub_field') ? get_sub_field('hero_secondary_button_text') : '';
$secondary_url   = function_exists('get_sub_field') ? get_sub_field('hero_secondary_button_url') : '';
$experience_url  = function_exists('get_sub_field') ? get_sub_field('hero_experience_url') : '';
$experience_desc = function_exists('get_sub_field') ? get_sub_field('hero_experience_description') : 'Een onvergetelijke merkbeleving neerzetten? We got you! Boozed vertaalt jouw idee om tot een experience die je voelt, ziet én onthoudt. Ontdek onze werkwijze';
$experience_read = function_exists('get_sub_field') ? get_sub_field('hero_experience_read_more') : 'Lees meer.';
$cursor_text     = function_exists('get_sub_field') ? get_sub_field('hero_cursor_text') : '';
$cursor_text     = $cursor_text ?: 'Lees meer.';
$rental_url      = function_exists('get_sub_field') ? get_sub_field('hero_rental_url') : '';
$rental_desc     = function_exists('get_sub_field') ? get_sub_field('hero_rental_description') : '';
$fabrications_url = function_exists('get_sub_field') ? get_sub_field('hero_fabrications_url') : '';
$fabrications_desc = function_exists('get_sub_field') ? get_sub_field('hero_fabrications_description') : '';

// Fallback to mega menu service URLs when hero ACF URLs are empty (by order: Experience, Rental, Fabrications)
if (class_exists('App\NavWalker')) {
	$mega_items = \App\NavWalker::get_mega_menu_items();
	if (empty($experience_url) && !empty($mega_items[0]['url'])) {
		$experience_url = $mega_items[0]['url'];
	}
	if (empty($rental_url) && !empty($mega_items[1]['url'])) {
		$rental_url = $mega_items[1]['url'];
	}
	if (empty($fabrications_url) && !empty($mega_items[2]['url'])) {
		$fabrications_url = $mega_items[2]['url'];
	}
}

$heading         = $heading ?: 'Wij maken beleving tastbaar.';
$use_video       = ($backdrop_type === 'video' && $video_url);
$image_url       = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
$has_backdrop    = $use_video || $image_url;
$cursor_svg_url  = get_template_directory_uri() . '/assets/images/custom-cursor-shape.svg';

$show_primary_btn   = $primary_url && $primary_label;
$show_secondary_btn = $secondary_url && $secondary_label;
?>
<section class="hero relative w-full min-h-[100svh] md:h-auto md:min-h-screen flex flex-col overflow-visible <?php echo $has_backdrop ? '' : 'bg-brand-indigo'; ?>">
	<?php if ($has_backdrop) : ?>
		<div class="hero__backdrop absolute inset-0 z-0 overflow-x-hidden" aria-hidden="true">
			<?php if ($use_video) : ?>
				<video class="hero__video absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline>
					<source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
				</video>
				<button type="button" class="hero__sound absolute right-4 bottom-8 md:bottom-12 z-10 flex items-center gap-2 text-brand-white text-body-sm font-body" aria-label="<?php esc_attr_e('Sound on', 'boozed'); ?>">
					<span class="hero__sound-icon hero__sound-icon--on relative block w-8 h-8">
						<svg class="absolute inset-0 w-full h-full" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
					</span>
					<span class="hero__sound-icon hero__sound-icon--off hidden relative block w-8 h-8">
						<svg class="absolute inset-0 w-full h-full" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
					</span>
					<span class="hero__sound-label"><?php esc_html_e('Sound on', 'boozed'); ?></span>
				</button>
			<?php else : ?>
				<div class="absolute inset-0 bg-cover bg-center" style="background-image: url(<?php echo esc_url($image_url); ?>);"></div>
			<?php endif; ?>
			<div class="absolute inset-0 bg-brand-black/50" aria-hidden="true"></div>
			<div id="hero-mega-menu-overlay" class="hero__mega-menu-overlay absolute inset-0 z-[1] bg-black/60 pointer-events-none" style="opacity:0;" aria-hidden="true"></div>
		</div>
	<?php endif; ?>

	<div class="hero__content relative z-10 flex flex-1 min-h-0 flex-col justify-center items-center text-center w-full box-border overflow-y-visible overflow-x-visible">
		<div class="max-w-section mx-auto px-4 md:px-section-x py-section-y w-full overflow-x-hidden">
		<h1 class="font-heading font-bold text-h1 md:text-h1-lg text-brand-white mb-6 md:mb-8 break-words"><?php echo esc_html($heading); ?></h1>
		<div class="flex flex-wrap items-center justify-center gap-4 md:gap-6">
			<?php if ($show_primary_btn) : ?>
				<?php
				$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
				\App\Components::render('button', [
					'variant'        => 'coral',
					'label'          => $primary_label,
					'href'           => $primary_url,
					'icon_right_html' => $phosphor_chevron_right,
					'class'          => '!bg-brand-coral',
				]);
				?>
			<?php endif; ?>
			<?php if ($show_secondary_btn) : ?>
				<a href="<?php echo esc_url($secondary_url); ?>" class="hero__cta-secondary font-body text-body-md font-medium text-brand-white no-underline relative inline-block hover:opacity-90 transition-opacity">
					<?php echo esc_html($secondary_label); ?>
				</a>
			<?php endif; ?>
		</div>
		</div>
	</div>

	<div class="hero__nav-bar relative z-[15] w-full flex-shrink-0 bg-brand-indigo flex flex-wrap overflow-visible">
		<?php
		$cards = [
			[
				'label'       => 'Experience',
				'url'         => $experience_url,
				'description' => $experience_desc,
				'width_class' => 'w-full md:w-1/3',
				'always_purple' => true,
			],
			[
				'label'       => 'Rental',
				'url'         => $rental_url,
				'description' => $rental_desc,
				'width_class' => 'w-full md:w-1/3',
			],
			[
				'label'       => 'Fabrications',
				'url'         => $fabrications_url,
				'description' => $fabrications_desc,
				'width_class' => 'w-full md:w-1/3',
			],
		];
		foreach ($cards as $card) :
			$tag = ! empty($card['url']) ? 'a' : 'div';
			$href_attr = ! empty($card['url']) ? ' href="' . esc_url($card['url']) . '"' : '';
			$width_class = isset($card['width_class']) ? $card['width_class'] : 'w-full md:w-1/3';
			$has_content = ! empty($card['description']);
			$always_purple = ! empty($card['always_purple']);
			$item_class = 'hero-nav-item group relative block min-w-0 py-3 md:py-6 px-4 md:px-12 text-brand-white transition-colors ' . esc_attr($width_class);
			$item_class .= $always_purple ? ' bg-brand-purple' : ' hover:bg-brand-purple';
		?>
		<<?php echo $tag; ?> class="<?php echo esc_attr($item_class); ?>"<?php echo $href_attr; ?>>
			<?php if ($has_content) : ?>
			<div class="hero-nav-item__content absolute bottom-full left-0 right-0 z-[20] w-full bg-brand-purple rounded-t-lg p-4 pb-6 md:p-10 md:pb-12 opacity-0 invisible pointer-events-none md:pointer-events-auto hidden md:block overflow-visible" style="cursor:none;" aria-hidden="true">
				<p class="font-body text-body-sm md:text-body-md text-brand-white leading-relaxed pb-1 m-0"><?php echo esc_html($card['description']); ?></p>
			</div>
			<?php endif; ?>
			<div class="relative z-10 flex items-center justify-between gap-3 min-h-[2.75rem] md:min-h-0">
				<span class="font-heading font-bold text-lg md:text-h2-lg truncate min-w-0">
					<?php echo esc_html($card['label']); ?>
				</span>
				<svg class="w-5 h-5 md:w-6 md:h-6 shrink-0 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="#C41E3A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
			</div>
		</<?php echo $tag; ?>>
		<?php endforeach; ?>
	</div>

	<!-- Custom cursor blob -->
	<div id="hero-custom-cursor" class="pointer-events-none fixed z-50" style="display:none;width:75px;height:75px;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white font-body text-body-xs font-medium leading-tight text-center px-2"><?php echo esc_html($cursor_text); ?></span>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_hero_nav_script_printed'])) : $GLOBALS['boozed_hero_nav_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		/* ── Sound toggle (video heroes) ── */
		var soundOn = '<?php echo esc_js(__('Sound on', 'boozed')); ?>';
		var soundOff = '<?php echo esc_js(__('Sound off', 'boozed')); ?>';
		document.querySelectorAll('.hero').forEach(function(hero) {
			var video = hero.querySelector('.hero__video');
			var soundBtn = hero.querySelector('.hero__sound');
			if (!video || !soundBtn) return;
			var label = hero.querySelector('.hero__sound-label');
			var iconOn = hero.querySelector('.hero__sound-icon--on');
			var iconOff = hero.querySelector('.hero__sound-icon--off');
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

		/* ── GSAP: content divs + custom cursor ── */
		if (typeof gsap === 'undefined') return;

		var navBar   = document.querySelector('.hero__nav-bar');
		var navItems = document.querySelectorAll('.hero-nav-item');
		var cursor   = document.getElementById('hero-custom-cursor');
		var cursorActive = false;
		var hideCursorTimeout = null;
		var HIDE_DELAY_MS = 120;

		function cancelHideCursor() {
			if (hideCursorTimeout) {
				clearTimeout(hideCursorTimeout);
				hideCursorTimeout = null;
			}
		}

		/* Custom cursor: show when entering nav bar or any item/content; hide only after leaving the whole zone (with delay so moving between items or to popup doesn't flicker) */
		if (navBar) {
			navBar.addEventListener('mouseenter', function() {
				cancelHideCursor();
				showCursor();
			});
			navBar.addEventListener('mouseleave', function() {
				hideCursorTimeout = setTimeout(hideCursor, HIDE_DELAY_MS);
			});
			navBar.addEventListener('mousemove', moveCursor);
		}

		/* When entering any item or its content popup, keep cursor visible (cancel delayed hide) */
		navItems.forEach(function(item) {
			var content = item.querySelector('.hero-nav-item__content');
			item.addEventListener('mouseenter', function() {
				cancelHideCursor();
				showCursor();
			});
			if (content) {
				content.addEventListener('mouseenter', function() {
					cancelHideCursor();
					showCursor();
				});
				content.addEventListener('mousemove', moveCursor);
			}
		});

		/* Animate content divs on hover */
		navItems.forEach(function(item) {
			var content = item.querySelector('.hero-nav-item__content');

			item.addEventListener('mouseenter', function() {
				if (content) {
					gsap.killTweensOf(content);
					gsap.set(content, { clearProps: 'transform' });
					gsap.to(content, {
						opacity: 1,
						visibility: 'visible',
						duration: 0.35,
						ease: 'power3.out',
					});
				}
			});

			item.addEventListener('mouseleave', function() {
				if (content) {
					gsap.killTweensOf(content);
					gsap.to(content, {
						opacity: 0,
						duration: 0.25,
						ease: 'power2.in',
						onComplete: function() {
							content.style.visibility = 'hidden';
							gsap.set(content, { clearProps: 'transform' });
						}
					});
				}
			});
		});

		/* Custom cursor helpers */
		function showCursor() {
			if (!cursor || cursorActive) return;
			cursorActive = true;
			cursor.style.display = 'block';
			gsap.fromTo(cursor,
				{ scale: 0.4, opacity: 0 },
				{ scale: 1, opacity: 1, duration: 0.3, ease: 'back.out(1.7)' }
			);
		}

		function hideCursor() {
			if (!cursor) return;
			cancelHideCursor();
			cursorActive = false;
			gsap.to(cursor, {
				scale: 0.4,
				opacity: 0,
				duration: 0.2,
				ease: 'power2.in',
				onComplete: function() { cursor.style.display = 'none'; }
			});
		}

		function moveCursor(e) {
			if (!cursor) return;
			gsap.to(cursor, {
				x: e.clientX - 37,
				y: e.clientY - 37,
				duration: 0.15,
				ease: 'power2.out',
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
<?php endif; ?>
