<?php

/**
 * Steps section
 * Up to 10 steps: image (left), eyebrow, heading, content, list items (right). Bottom nav with numbers; auto-play and click to step. Custom cursor (custom-cursor-shape.svg + "Next") on number hover.
 */

$raw_items = function_exists('get_sub_field') ? (array) get_sub_field('steps_items') : [];
$raw_items = array_slice($raw_items, 0, 10);

$steps = [];
$placeholder_img = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

foreach ($raw_items as $item) {
	$heading = isset($item['heading']) ? trim((string) $item['heading']) : '';
	$img_id = isset($item['image']) ? (int) $item['image'] : 0;
	if ($heading === '' && ! $img_id) {
		continue;
	}
	$img_url    = $img_id ? wp_get_attachment_image_url($img_id, 'large') : $placeholder_img;
	$eyebrow    = isset($item['eyebrow']) ? (string) $item['eyebrow'] : '';
	$content    = isset($item['content']) ? (string) $item['content'] : '';
	$list_rows  = isset($item['list_items']) && is_array($item['list_items']) ? $item['list_items'] : [];
	$list_labels = [];
	foreach ($list_rows as $row) {
		$label = isset($row['label']) ? trim((string) $row['label']) : '';
		if ($label !== '') {
			$list_labels[] = $label;
		}
	}
	$steps[] = [
		'image_url' => $img_url,
		'eyebrow'   => $eyebrow,
		'heading'   => $heading,
		'content'   => $content,
		'list'      => $list_labels,
	];
}

/* Phosphor CheckCircle (coral) for list items */
$checkmark_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 256 256" fill="currentColor" class="steps__check-icon shrink-0" aria-hidden="true"><path d="M173.66 98.34a8 8 0 0 1 0 11.32l-56 56a8 8 0 0 1-11.32 0l-24-24a8 8 0 0 1 11.32-11.32L112 148.69l50.34-50.35a8 8 0 0 1 11.32 0ZM232 128A104 104 0 1 1 128 24a104.11 104.11 0 0 1 104 104Zm-16 0a88 88 0 1 0-88 88a88.1 88.1 0 0 0 88-88Z"/></svg>';

$cursor_svg_url = get_template_directory_uri() . '/assets/images/custom-cursor-shape.svg';

if (empty($steps)) {
	return;
}

$first = $steps[0];
$steps_json = wp_json_encode($steps);
?>

<section class="steps max-w-section mx-auto py-10 md:py-section-y"
         data-steps="<?php echo esc_attr($steps_json); ?>">
	<div class="steps__content grid grid-cols-1 md:grid-cols-2 md:items-stretch">
		<div class="steps__image-wrap overflow-hidden bg-brand-black/10">
			<img src="<?php echo esc_url($first['image_url']); ?>"
			     alt=""
			     class="steps__image w-full h-full object-cover transition-opacity duration-300"
			     loading="eager">
		</div>
		<div class="steps__text flex flex-col justify-center bg-brand-white px-6 py-8 md:px-12 md:py-12" aria-live="polite">
			<p class="steps__eyebrow steps__anim-el font-body text-body-sm text-brand-indigo/60 mb-2" <?php echo $first['eyebrow'] === '' ? ' style="display:none;"' : ''; ?>><?php echo esc_html($first['eyebrow']); ?></p>
			<h2 class="steps__heading steps__anim-el font-heading font-bold text-h3 md:text-h2 text-brand-indigo mb-4" <?php echo $first['heading'] === '' ? ' style="display:none;"' : ''; ?>><?php echo esc_html($first['heading']); ?></h2>
			<div class="steps__body steps__anim-el steps__wysiwyg font-body text-body text-brand-indigo/80 mb-6" <?php echo $first['content'] === '' ? ' style="display:none;"' : ''; ?>><?php echo wp_kses_post($first['content']); ?></div>
			<ul class="steps__list steps__anim-el list-none p-0 m-0 space-y-3" <?php echo empty($first['list']) ? ' style="display:none;"' : ''; ?>>
				<?php foreach ($first['list'] as $label) : ?>
					<li class="steps__list-item flex items-center gap-3">
						<span class="steps__check text-brand-coral"><?php echo $checkmark_svg; ?></span>
						<span class="font-body text-body text-brand-indigo font-semibold"><?php echo esc_html($label); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<nav class="steps__nav flex" aria-label="<?php esc_attr_e('Steps', 'boozed'); ?>">
		<?php foreach ($steps as $i => $step) : ?>
			<button type="button"
			        class="steps__nav-btn relative font-heading font-bold text-body-md md:text-body-lg flex-1 bg-brand-indigo text-brand-white border-0 cursor-pointer transition-colors focus:outline-none focus:ring-0 overflow-hidden <?php echo $i === 0 ? 'steps__nav-btn--active' : ''; ?>"
			        data-step-index="<?php echo (int) $i; ?>"
			        aria-current="<?php echo $i === 0 ? 'step' : 'false'; ?>">
				<span class="steps__nav-progress"></span>
				<span class="relative z-10"><?php echo (int) $i + 1; ?>.</span>
			</button>
		<?php endforeach; ?>
	</nav>

	<!-- Custom cursor (same blob shape as hero/projects_lister + "Next" label). Moved to body by JS. -->
	<div id="steps-custom-cursor" class="pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white font-body text-body-xs font-medium leading-tight text-center px-2">Next</span>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_steps_script_printed'])) : $GLOBALS['boozed_steps_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		document.querySelectorAll('.steps').forEach(function(section) {
			var dataStr = section.getAttribute('data-steps');
			if (!dataStr) return;
			var steps;
			try {
				steps = JSON.parse(dataStr);
			} catch (e) {
				return;
			}
			if (!steps.length) return;

			var imgEl = section.querySelector('.steps__image');
			var eyebrowEl = section.querySelector('.steps__eyebrow');
			var headingEl = section.querySelector('.steps__heading');
			var bodyEl = section.querySelector('.steps__body');
			var listEl = section.querySelector('.steps__list');
			var navBtns = section.querySelectorAll('.steps__nav-btn');
			var animEls = section.querySelectorAll('.steps__anim-el');

			var currentIndex = 0;
			var autoPlayTimer = null;
			var AUTO_PLAY_MS = 5000;

			function resetAllProgress() {
				navBtns.forEach(function(btn) {
					var bar = btn.querySelector('.steps__nav-progress');
					if (bar) {
						bar.style.transition = 'none';
						bar.style.width = '0%';
					}
				});
			}

			function animateProgress(idx, duration) {
				var btn = navBtns[idx];
				if (!btn) return;
				var bar = btn.querySelector('.steps__nav-progress');
				if (!bar) return;
				bar.style.transition = 'none';
				bar.style.width = '0%';
				void bar.offsetWidth;
				bar.style.transition = 'width ' + duration + 'ms linear';
				bar.style.width = '100%';
			}

			function animateContentIn() {
				var els = section.querySelectorAll('.steps__anim-el');
				if (typeof gsap === 'undefined') return;
				gsap.fromTo(els,
					{ opacity: 0, y: 18 },
					{
						opacity: 1,
						y: 0,
						duration: 0.5,
						ease: 'power3.out',
						stagger: 0.08,
						clearProps: 'transform'
					}
				);
			}

			function setStepContent(i) {
				var step = steps[i];
				if (!step) return;
				if (imgEl) imgEl.src = step.image_url;
				if (eyebrowEl) {
					eyebrowEl.textContent = step.eyebrow || '';
					eyebrowEl.style.display = step.eyebrow ? '' : 'none';
				}
				if (headingEl) {
					headingEl.textContent = step.heading || '';
					headingEl.style.display = step.heading ? '' : 'none';
				}
				if (bodyEl) {
					bodyEl.innerHTML = step.content || '';
					bodyEl.style.display = step.content ? '' : 'none';
				}
				if (listEl) {
					if (step.list && step.list.length) {
						listEl.innerHTML = step.list.map(function(label) {
							return '<li class="steps__list-item flex items-center gap-3"><span class="steps__check text-brand-coral"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 256 256" fill="currentColor" class="steps__check-icon shrink-0" aria-hidden="true"><path d="M173.66 98.34a8 8 0 0 1 0 11.32l-56 56a8 8 0 0 1-11.32 0l-24-24a8 8 0 0 1 11.32-11.32L112 148.69l50.34-50.35a8 8 0 0 1 11.32 0ZM232 128A104 104 0 1 1 128 24a104.11 104.11 0 0 1 104 104Zm-16 0a88 88 0 1 0-88 88a88.1 88.1 0 0 0 88-88Z"/></svg></span><span class="font-body text-body text-brand-indigo font-semibold">' + (label.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')) + '</span></li>';
						}).join('');
						listEl.style.display = '';
					} else {
						listEl.innerHTML = '';
						listEl.style.display = 'none';
					}
				}
				navBtns.forEach(function(btn, idx) {
					btn.classList.toggle('steps__nav-btn--active', idx === i);
					btn.setAttribute('aria-current', idx === i ? 'step' : 'false');
				});
				currentIndex = i;
				animateContentIn();
			}

			function goToStep(i) {
				i = (i + steps.length) % steps.length;
				resetAllProgress();
				setStepContent(i);
				animateProgress(i, AUTO_PLAY_MS);
			}

			function startAutoPlay() {
				stopAutoPlay();
				animateProgress(currentIndex, AUTO_PLAY_MS);
				autoPlayTimer = setInterval(function() {
					goToStep(currentIndex + 1);
				}, AUTO_PLAY_MS);
			}

			function stopAutoPlay() {
				if (autoPlayTimer) {
					clearInterval(autoPlayTimer);
					autoPlayTimer = null;
				}
			}

			navBtns.forEach(function(btn, idx) {
				btn.addEventListener('click', function() {
					stopAutoPlay();
					resetAllProgress();
					setStepContent(idx);
					var bar = btn.querySelector('.steps__nav-progress');
					if (bar) {
						bar.style.transition = 'none';
						bar.style.width = '100%';
					}
					startAutoPlay();
				});
			});

			/* Initial entrance animation */
			animateContentIn();
			startAutoPlay();
		});

		/* Custom cursor on steps nav hover */
		var cursor = document.getElementById('steps-custom-cursor');
		var navs = document.querySelectorAll('.steps__nav');
		if (cursor && navs.length && typeof gsap !== 'undefined') {
			if (cursor.parentNode !== document.body) document.body.appendChild(cursor);
			var cursorActive = false;
			var hideCursorTimeout = null;
			var HIDE_DELAY_MS = 120;
			var cursorWidth = 75;
			var cursorHeight = 75;

			function cancelHideCursor() {
				if (hideCursorTimeout) { clearTimeout(hideCursorTimeout); hideCursorTimeout = null; }
			}
			function showCursor() {
				if (!cursor || cursorActive) return;
				cursorActive = true;
				cursor.style.display = 'flex';
				gsap.fromTo(cursor, { scale: 0.4, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.3, ease: 'back.out(1.7)' });
			}
			function hideCursor() {
				if (!cursor) return;
				cancelHideCursor();
				cursorActive = false;
				gsap.to(cursor, { scale: 0.4, opacity: 0, duration: 0.2, ease: 'power2.in', onComplete: function() { cursor.style.display = 'none'; } });
			}
			function moveCursor(e) {
				if (!cursor) return;
				gsap.to(cursor, { x: e.clientX - cursorWidth / 2, y: e.clientY - cursorHeight / 2, duration: 0.15, ease: 'power2.out' });
			}

			navs.forEach(function(nav) {
				nav.addEventListener('mouseenter', function(ev) {
					if (window.matchMedia('(min-width: 768px)').matches) {
						cancelHideCursor();
						showCursor();
						moveCursor(ev);
					}
				});
				nav.addEventListener('mouseleave', function() {
					hideCursorTimeout = setTimeout(hideCursor, HIDE_DELAY_MS);
				});
				nav.addEventListener('mousemove', function(ev) {
					if (window.matchMedia('(min-width: 768px)').matches) moveCursor(ev);
				});
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

<style>
/* Steps: image aspect ratio 720x741 */
.steps .steps__image-wrap {
	aspect-ratio: 720 / 741;
	min-height: 0;
}
@media (min-width: 768px) {
	.steps .steps__image-wrap {
		aspect-ratio: 720 / 741;
		min-height: 320px;
	}
}

/* Nav bar: full width, square buttons */
.steps .steps__nav {
	width: 100%;
}
.steps .steps__nav-btn {
	text-align: center;
	aspect-ratio: 1 / 1;
	display: flex;
	align-items: center;
	justify-content: center;
}
/* Hover: highlight box with Purple/25 */
.steps .steps__nav-btn::after {
	content: '';
	position: absolute;
	inset: 0;
	background-color: rgb(49 39 131 / 0.25); /* brand-purple #312783 at 25% */
	z-index: 2;
	pointer-events: none;
	opacity: 0;
	transition: opacity 0.2s ease;
}
.steps .steps__nav-btn:hover::after {
	opacity: 1;
}

/* Progress bar inside each nav button */
.steps .steps__nav-progress {
	position: absolute;
	left: 0;
	top: 0;
	bottom: 0;
	width: 0%;
	background-color: #312783;
	z-index: 1;
	pointer-events: none;
}

/* Active button: progress filled */
.steps .steps__nav-btn--active .steps__nav-progress {
	width: 100%;
}

/* WYSIWYG content styling inside steps (dark text on white) */
.steps__wysiwyg p {
	margin: 0 0 1em;
}
.steps__wysiwyg p:last-child {
	margin-bottom: 0;
}
.steps__wysiwyg h1,
.steps__wysiwyg h2,
.steps__wysiwyg h3,
.steps__wysiwyg h4,
.steps__wysiwyg h5,
.steps__wysiwyg h6 {
	font-family: 'Nexa', sans-serif;
	font-weight: 700;
	color: #0C0A21;
	margin: 0 0 0.75em;
}
.steps__wysiwyg h1:first-child,
.steps__wysiwyg h2:first-child,
.steps__wysiwyg h3:first-child,
.steps__wysiwyg h4:first-child,
.steps__wysiwyg h5:first-child,
.steps__wysiwyg h6:first-child {
	margin-top: 0;
}
.steps__wysiwyg ul,
.steps__wysiwyg ol {
	margin: 0 0 1em;
	padding-left: 1.5em;
}
.steps__wysiwyg li {
	margin-bottom: 0.35em;
}
.steps__wysiwyg a {
	color: #E83F44;
	text-decoration: underline;
	transition: opacity 0.2s;
}
.steps__wysiwyg a:hover {
	opacity: 0.8;
}
.steps__wysiwyg strong,
.steps__wysiwyg b {
	font-weight: 600;
	color: #0C0A21;
}
.steps__wysiwyg em,
.steps__wysiwyg i {
	font-style: italic;
}
.steps__wysiwyg blockquote {
	border-left: 3px solid #312783;
	margin: 0 0 1em;
	padding: 0.5em 1em;
	color: rgba(12, 10, 33, 0.6);
}

/* Custom cursor: hide system cursor on nav (desktop only) */
@media (min-width: 768px) {
	.steps .steps__nav,
	.steps .steps__nav * {
		cursor: none;
	}
}
@media (max-width: 767px) {
	.steps .steps__nav,
	.steps .steps__nav * {
		cursor: pointer;
	}
}
/* Steps custom cursor: blob shape + label */
#steps-custom-cursor {
	display: none;
	transform-origin: center center;
}
</style>
