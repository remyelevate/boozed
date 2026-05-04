<?php

/**
 * Brands section
 * Title and CTA button centered; brand logos absolutely positioned in hand-picked spots around the title.
 * Slots are filled by cycling through brands so the section always looks full. Luminosity filter + hover reveal.
 * Background: one-shot video (brush_04.mov) when section enters view.
 */

$title         = function_exists('get_sub_field') ? (string) get_sub_field('brands_title') : '';
$button_label  = function_exists('get_sub_field') ? (string) get_sub_field('brands_button_label') : '';
$button_url    = function_exists('get_sub_field') ? (string) get_sub_field('brands_button_url') : '';
$brands_items  = function_exists('get_sub_field') ? (array) get_sub_field('brands_items') : [];

$show_button   = $button_url !== '' && $button_label !== '';

/* 12 hand-picked slots around the title. Fill by cycling through brands. */
$brands_slots_count = 12;
$brands_fill = [];
if ( ! empty($brands_items)) {
	$valid = array_filter($brands_items, function ($item) {
		$img_id = isset($item['image']) ? (int) $item['image'] : 0;
		return $img_id && wp_get_attachment_image_url($img_id, 'medium');
	});
	$valid = array_values($valid);
	if ( ! empty($valid)) {
		for ($i = 0; $i < $brands_slots_count; $i++) {
			$brands_fill[] = $valid[$i % count($valid)];
		}
	}
}

/*
 * 12 hand-picked positions (top/left or top/right as %).
 * Arranged in 3 bands: above title, flanking title, below title.
 */
$slot_positions = [
	/* ── top band ── */
	0  => 'top:8%;left:5%',
	1  => 'top:5%;left:30%',
	2  => 'top:10%;right:25%;left:auto',
	3  => 'top:4%;right:4%;left:auto',
	/* ── middle band (flanking title) ── */
	4  => 'top:35%;left:2%',
	5  => 'top:44%;left:16%',
	6  => 'top:34%;right:14%;left:auto',
	7  => 'top:46%;right:1%;left:auto',
	/* ── bottom band ── */
	8  => 'bottom:10%;left:7%',
	9  => 'bottom:6%;left:28%',
	10 => 'bottom:12%;right:24%;left:auto',
	11 => 'bottom:5%;right:6%;left:auto',
];

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
$brands_video_dir = get_template_directory_uri() . '/assets/images';
?>
<section class="brands relative overflow-hidden min-h-[100dvh] md:min-h-[1024px]">
	<div class="brands__video-wrap absolute inset-0 z-0 w-full h-full pointer-events-none" aria-hidden="true">
		<video class="brands__video w-full h-full object-cover" muted playsinline preload="auto">
			<source src="<?php echo esc_url($brands_video_dir . '/paint-fill.mp4'); ?>" type="video/mp4">
		</video>
	</div>
	<div class="brands__inner relative z-[1] max-w-section mx-auto px-4 md:px-section-x w-full h-[100dvh] md:h-[1024px] flex items-center justify-center">
		<?php /* Scattered logos – absolutely positioned via inline styles */ ?>
		<?php if ( ! empty($brands_fill)) : ?>
			<?php foreach ($brands_fill as $i => $item) :
				$img_id  = (int) $item['image'];
				$url     = isset($item['url']) ? esc_url($item['url']) : '';
				$img_src = wp_get_attachment_image_url($img_id, 'medium');
				if ( ! $img_src) continue;
				$tag       = $url ? 'a' : 'span';
				$href_attr = $url ? ' href="' . $url . '"' : '';
				$pos_style = isset($slot_positions[$i]) ? $slot_positions[$i] : 'top:50%;left:50%';
			?>
			<?php $mobile_visibility = in_array($i, [0, 1, 2, 3, 8, 10], true) ? 'flex' : 'hidden md:flex'; ?>
			<<?php echo $tag; ?> class="brands__logo brands__logo--slot-<?php echo (int) $i; ?> <?php echo esc_attr($mobile_visibility); ?>" style="<?php echo esc_attr($pos_style); ?>"<?php echo $href_attr; ?>>
				<img src="<?php echo esc_url($img_src); ?>" alt="" class="brands__logo-img" loading="lazy">
			</<?php echo $tag; ?>>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php /* Centered title and button */ ?>
		<div class="brands__content relative z-10 text-center mx-auto">
			<?php if ($title !== '') : ?>
				<h2 class="brands__title font-heading font-bold text-h1 md:text-h1-lg text-brand-white mb-6 md:mb-8"><?php echo esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($show_button) : ?>
				<?php
				\App\Components::render('button', [
					'variant'          => 'coral',
					'label'            => $button_label,
					'href'             => $button_url,
					'icon_right_html'  => $phosphor_chevron_right,
					'class'            => '!bg-brand-coral',
				]);
				?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_brands_script_printed'])) : $GLOBALS['boozed_brands_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		var section = document.querySelector('.brands');
		if (!section) return;

		var logos = section.querySelectorAll('.brands__logo');
		if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
			gsap.registerPlugin(ScrollTrigger);

			/* Staggered fade-in-up on scroll (delayed so paint-fill video leads); clear inline styles after so CSS hover works */
			gsap.fromTo(logos, { opacity: 0, y: 24, scale: 0.8 }, {
				opacity: 0.6,
				y: 0,
				scale: 1,
				duration: 0.5,
				delay: 2,
				stagger: 0.08,
				ease: 'back.out(1.4)',
				onComplete: function() {
					logos.forEach(function(el) {
						el.style.removeProperty('opacity');
						el.style.removeProperty('transform');
					});
				},
				scrollTrigger: {
					trigger: section,
					start: 'top 80%',
					toggleActions: 'play none none none',
				},
			});

		} else {
			logos.forEach(function(logo) { logo.style.opacity = '1'; });
		}

		/* One-shot video background when section enters view */
		var video = section.querySelector('.brands__video');
		if (video && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			video.muted = true;
			video.playsInline = true;
			var played = false;
			function tryPlay() {
				if (played) return;
				played = true;
				function doPlay() {
					video.play().catch(function() { played = false; });
				}
				if (video.readyState >= 2) {
					doPlay();
				} else {
					video.addEventListener('canplay', doPlay, { once: true });
				}
			}
			var observer = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (!entry.isIntersecting) return;
					tryPlay();
					observer.disconnect();
				});
			}, { threshold: 0.1, rootMargin: '0px 0px 50px 0px' });
			observer.observe(section);
			// #region agent log
			video.addEventListener('loadeddata', function() {
				fetch('http://127.0.0.1:7247/ingest/4d3c4cc4-9bd2-4f0d-b505-1ee467ae6036', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ location: 'brands.php:video', message: 'video loaded', timestamp: Date.now(), data: { videoWidth: video.videoWidth, videoHeight: video.videoHeight, readyState: video.readyState }, hypothesisId: 'verify', runId: 'post-fix' }) }).catch(function() {});
			}, { once: true });
			video.addEventListener('error', function() {
				fetch('http://127.0.0.1:7247/ingest/4d3c4cc4-9bd2-4f0d-b505-1ee467ae6036', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ location: 'brands.php:video', message: 'video error', timestamp: Date.now(), data: { error: video.error ? video.error.message : 'unknown', code: video.error ? video.error.code : null }, hypothesisId: 'verify', runId: 'post-fix' }) }).catch(function() {});
			}, { once: true });
			// #endregion
		}

		// #region agent log
		setTimeout(function() {
			var wrap = section.querySelector('.brands__video-wrap');
			var vid = section.querySelector('.brands__video');
			var inner = section.querySelector('.brands__inner');
			function rect(o) { if (!o) return null; var r = o.getBoundingClientRect(); return { w: r.width, h: r.height, top: r.top, left: r.left }; }
			function comp(el, prop) { if (!el) return null; return window.getComputedStyle(el).getPropertyValue(prop); }
			var payload = {
				location: 'brands.php:init',
				message: 'brands layout and video visibility',
				timestamp: Date.now(),
				data: {
					section: { rect: rect(section), offsetW: section.offsetWidth, offsetH: section.offsetHeight, bg: comp(section, 'background-color') },
					videoWrap: wrap ? { rect: rect(wrap), offsetW: wrap.offsetWidth, offsetH: wrap.offsetHeight, zIndex: comp(wrap, 'z-index'), position: comp(wrap, 'position'), opacity: comp(wrap, 'opacity'), visibility: comp(wrap, 'visibility'), display: comp(wrap, 'display') } : null,
					video: vid ? { rect: rect(vid), offsetW: vid.offsetWidth, offsetH: vid.offsetHeight, videoWidth: vid.videoWidth, videoHeight: vid.videoHeight, readyState: vid.readyState, zIndex: comp(vid, 'z-index'), opacity: comp(vid, 'opacity'), visibility: comp(vid, 'visibility'), display: comp(vid, 'display') } : null,
					inner: inner ? { rect: rect(inner), offsetW: inner.offsetWidth, offsetH: inner.offsetHeight, zIndex: comp(inner, 'z-index'), bg: comp(inner, 'background-color') } : null
				},
				hypothesisId: 'H1-H5'
			};
			fetch('http://127.0.0.1:7247/ingest/4d3c4cc4-9bd2-4f0d-b505-1ee467ae6036', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }).catch(function() {});
		}, 500);
		// #endregion
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
<?php endif; ?>
