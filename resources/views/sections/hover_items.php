<?php

/**
 * Hover items section
 * Two columns: left = h4 title, intro body, list of items (checkmark + h5 label + cursor label).
 * Right = single image (713x872 ratio); hover an item to show its image, fallback when none hovered.
 * Custom cursor (blob shape + ACF label) on list hover (desktop).
 */

$intro_heading   = function_exists('get_sub_field') ? (string) get_sub_field('hover_items_intro_heading') : '';
$intro_body      = function_exists('get_sub_field') ? (string) get_sub_field('hover_items_intro_body') : '';
$default_img_id  = function_exists('get_sub_field') ? (int) get_sub_field('hover_items_default_image') : 0;
$cursor_label    = function_exists('get_sub_field') ? (string) get_sub_field('hover_items_cursor_label') : '';
$cursor_label    = $cursor_label !== '' ? $cursor_label : 'Lees meer';
$items           = function_exists('get_sub_field') ? array_slice((array) get_sub_field('hover_items_items'), 0, 6) : [];

$default_img_url = $default_img_id ? wp_get_attachment_image_url($default_img_id, 'full') : '';
$placeholder_img = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

/* Phosphor CheckCircle icon (regular weight, coral fill) */
$checkmark_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 256 256" fill="currentColor" class="hover-items__check-icon shrink-0" aria-hidden="true"><path d="M173.66 98.34a8 8 0 0 1 0 11.32l-56 56a8 8 0 0 1-11.32 0l-24-24a8 8 0 0 1 11.32-11.32L112 148.69l50.34-50.35a8 8 0 0 1 11.32 0ZM232 128A104 104 0 1 1 128 24a104.11 104.11 0 0 1 104 104Zm-16 0a88 88 0 1 0-88 88a88.1 88.1 0 0 0 88-88Z"/></svg>';
?>
<section class="hover-items max-w-section mx-auto px-4 py-8 sm:px-6 sm:py-10 md:px-0 md:py-section-y"
         data-default-image="<?php echo esc_url($default_img_url ?: $placeholder_img); ?>">
	<div class="hover-items__grid grid grid-cols-1 md:grid-cols-2 gap-0 md:items-stretch">
		<div class="hover-items__left flex flex-col pt-6 sm:pt-8 md:pt-[40px] pl-4 pr-4 sm:pl-6 sm:pr-6 md:pl-[68px] md:pr-0">
			<?php if ($intro_heading !== '') : ?>
				<h4 class="hover-items__intro-heading font-heading font-bold text-h4 md:text-h4-lg text-brand-indigo mb-3 sm:mb-4">
					<?php echo esc_html($intro_heading); ?>
				</h4>
			<?php endif; ?>
			<?php if ($intro_body !== '') : ?>
				<div class="hover-items__intro-body font-body text-body text-brand-indigo/80 mb-6 sm:mb-8 shrink-0">
					<?php echo nl2br(esc_html($intro_body)); ?>
				</div>
			<?php endif; ?>

			<div class="hover-items__list flex flex-1 flex-col min-h-0">
				<?php foreach ($items as $i => $item) :
					$label  = isset($item['label']) ? (string) $item['label'] : '';
					$img_id = isset($item['image']) ? (int) $item['image'] : 0;
					$img_url = $img_id ? wp_get_attachment_image_url($img_id, 'full') : '';
					if ($label === '') continue;
				?>
				<div class="hover-items__item flex items-center gap-3 border-t border-brand-border transition-colors duration-200"
				     data-item-image="<?php echo esc_url($img_url); ?>"
				     role="button"
				     tabindex="0"
				     aria-pressed="false">
					<span class="hover-items__check text-brand-coral"><?php echo $checkmark_svg; ?></span>
					<h5 class="hover-items__item-label font-heading font-bold text-h5 md:text-h5-lg text-brand-indigo"><?php echo esc_html($label); ?></h5>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="hover-items__right flex flex-col mt-6 sm:mt-8 md:mt-0 min-h-0">
			<div class="hover-items__image-wrap overflow-hidden">
				<img src="<?php echo esc_url($default_img_url ?: $placeholder_img); ?>" alt="" class="hover-items__image w-full h-full object-cover transition-opacity duration-300" loading="lazy">
			</div>
		</div>
	</div>

	<!-- Custom cursor (same blob shape as hero / projects_lister + label). Moved to body by JS. -->
	<div id="hover-items-custom-cursor" class="pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white font-body text-body-xs font-medium leading-tight text-center px-2"><?php echo esc_html($cursor_label); ?></span>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_hover_items_script_printed'])) : $GLOBALS['boozed_hover_items_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		document.querySelectorAll('.hover-items').forEach(function(section) {
			var leftCol = section.querySelector('.hover-items__left');
			var list = section.querySelector('.hover-items__list');
			var items = section.querySelectorAll('.hover-items__item');
			var img = section.querySelector('.hover-items__image');
			if (!leftCol || !list || !img) return;

			var defaultImage = section.getAttribute('data-default-image') || '';

			function resetToDefault() {
				img.src = defaultImage;
				items.forEach(function(it) { it.classList.remove('hover-items__item--active'); });
			}

			function showItem(item) {
				var url = item.getAttribute('data-item-image') || defaultImage;
				img.src = url;
				items.forEach(function(it) { it.classList.remove('hover-items__item--active'); });
				item.classList.add('hover-items__item--active');
			}

			items.forEach(function(item) {
				item.addEventListener('mouseenter', function() { showItem(item); });
				/* Tap/click for mobile and tablet: set active item and show image */
				item.addEventListener('click', function(e) {
					e.preventDefault();
					showItem(item);
					items.forEach(function(it) {
						it.setAttribute('aria-pressed', it === item ? 'true' : 'false');
					});
				});
				item.addEventListener('keydown', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						showItem(item);
						items.forEach(function(it) {
							it.setAttribute('aria-pressed', it === item ? 'true' : 'false');
						});
					}
				});
			});

			leftCol.addEventListener('mouseleave', function() {
				/* On desktop, reset image when leaving; on touch devices keep selection */
				if (window.matchMedia('(min-width: 768px)').matches) {
					resetToDefault();
					items.forEach(function(it) { it.setAttribute('aria-pressed', 'false'); });
				}
			});
		});

		/* Custom cursor (blob): show on list hover, hide on leave, move with mouse. Desktop only. */
		var cursor = document.getElementById('hover-items-custom-cursor');
		var lists = document.querySelectorAll('.hover-items__list');
		if (cursor && lists.length && typeof gsap !== 'undefined') {
			if (cursor.parentNode !== document.body) { document.body.appendChild(cursor); }
			var cursorActive = false;
			var hideCursorTimeout = null;
			var HIDE_DELAY_MS = 120;

			function cancelHideCursor() {
				if (hideCursorTimeout) { clearTimeout(hideCursorTimeout); hideCursorTimeout = null; }
			}
			function showCursor() {
				if (!cursor || cursorActive) return;
				cursorActive = true;
				cursor.style.display = 'block';
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
				gsap.to(cursor, { x: e.clientX - 37, y: e.clientY - 37, duration: 0.15, ease: 'power2.out' });
			}

			function setupCursorForList(listEl) {
				listEl.addEventListener('mouseenter', function(ev) {
					if (window.matchMedia('(min-width: 768px)').matches) {
						cancelHideCursor();
						showCursor();
						moveCursor(ev);
					}
				});
				listEl.addEventListener('mouseleave', function() {
					hideCursorTimeout = setTimeout(hideCursor, HIDE_DELAY_MS);
				});
				listEl.addEventListener('mousemove', function(ev) {
					if (window.matchMedia('(min-width: 768px)').matches) moveCursor(ev);
				});
			}
			lists.forEach(setupCursorForList);
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
/* Hover items: list rows – 128px height, responsive padding */
.hover-items .hover-items__item {
	flex: 0 0 128px;
	height: 128px;
	min-height: 128px;
	max-height: 128px;
	padding: 0 16px;
	align-items: center;
	cursor: pointer;
}
@media (min-width: 640px) {
	.hover-items .hover-items__item {
		padding: 0 20px 0 24px;
	}
}
@media (min-width: 768px) {
	.hover-items .hover-items__item {
		padding: 0 24px 0 68px;
		cursor: none;
	}
}

/* Active state: purple bg, white text & icon */
.hover-items .hover-items__item--active {
	background-color: #312783;
}
.hover-items .hover-items__item--active .hover-items__item-label {
	color: #FFFFFF;
}
.hover-items .hover-items__item--active .hover-items__check {
	color: #FFFFFF;
}

/* List full-width borders: negative margin matches column padding per breakpoint */
.hover-items .hover-items__list {
	margin-left: -16px;
	margin-right: 0;
}
@media (min-width: 640px) {
	.hover-items .hover-items__list {
		margin-left: -24px;
	}
}
@media (min-width: 768px) {
	.hover-items .hover-items__list {
		margin-left: -68px;
	}
}

/* Custom cursor: hide system cursor on list (desktop only) */
@media (min-width: 768px) {
	.hover-items .hover-items__list,
	.hover-items .hover-items__list * {
		cursor: none;
	}
}

/* Right column: 713x872 on desktop; slightly shorter ratio on small screens for better fit */
.hover-items .hover-items__image-wrap {
	aspect-ratio: 3 / 4;
	width: 100%;
	max-width: 100%;
}
@media (min-width: 640px) {
	.hover-items .hover-items__image-wrap {
		aspect-ratio: 713 / 872;
	}
}
@media (min-width: 768px) {
	.hover-items .hover-items__right {
		min-height: 0;
	}
}
</style>
