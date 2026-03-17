<?php

/**
 * FAQ section
 * Accordion: title + list of questions. Click to expand.
 * Red chevron indicates state (down = collapsed, up = expanded).
 */

$title    = function_exists('get_sub_field') ? (string) get_sub_field('faq_title') : '';
$faq_items = function_exists('get_sub_field') ? (array) get_sub_field('faq_items') : [];

$phosphor_chevron_down = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="faq__chevron w-5 h-5 shrink-0 transition-transform duration-200" aria-hidden="true"><path d="M213.66 101.66l-80 80a8 8 0 0 1-11.32 0l-80-80a8 8 0 0 1 11.32-11.32L128 164.69l74.34-74.35a8 8 0 0 1 11.32 11.32Z"/></svg>';
?>
<section class="faq max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y bg-transparent" data-faq-section>
	<?php if ($title !== '') : ?>
		<h2 class="faq__title font-heading font-bold text-h2 md:text-h2-lg text-[#242629] mb-6 md:mb-8" data-faq-title><?php echo esc_html($title); ?></h2>
	<?php endif; ?>

	<div class="faq__list border-t border-[#E0E0E0] -mx-4 md:-mx-section-x">
		<?php foreach ($faq_items as $i => $item) :
			$question = isset($item['question']) ? (string) $item['question'] : '';
			$answer   = isset($item['answer']) ? (string) $item['answer'] : '';
			if ($question === '') continue;
			$item_id = 'faq-item-' . $i;
		?>
		<div class="faq__item border-b border-[#E0E0E0] transition-colors duration-200" data-faq-item data-faq-index="<?php echo (int) $i; ?>">
			<button type="button"
			        class="faq__trigger w-full flex items-center justify-between gap-4 h-32 px-4 md:px-section-x text-left bg-transparent border-0 cursor-pointer font-heading font-bold text-body-md md:text-body-lg text-[#242629] focus:outline-none"
			        aria-expanded="false"
			        aria-controls="<?php echo esc_attr($item_id); ?>"
			        id="faq-trigger-<?php echo esc_attr($i); ?>">
				<span class="faq__question flex-1 min-w-0"><?php echo esc_html($question); ?></span>
				<span class="faq__icon text-[#ED1C24]" aria-hidden="true"><?php echo $phosphor_chevron_down; ?></span>
			</button>
			<div id="<?php echo esc_attr($item_id); ?>"
			     class="faq__panel overflow-hidden -mt-px"
			     role="region"
			     aria-labelledby="faq-trigger-<?php echo esc_attr($i); ?>"
			     data-faq-panel
			     hidden>
				<div class="faq__answer-wrap px-4 md:px-section-x pb-4 md:pb-5 pt-px">
					<div class="faq__answer font-body text-body text-[#6D6D6D] pt-0 pb-2 pl-6 prose prose-p:my-2 prose-p:first:mt-0 prose-p:last:mb-0 max-w-none">
						<?php echo wp_kses_post(wpautop($answer)); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_faq_script_printed'])) : $GLOBALS['boozed_faq_script_printed'] = true; ?>
<script>
(function() {
	var reducedMotion = typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function init() {
		document.querySelectorAll('.faq[data-faq-section]').forEach(function(section) {
			var titleEl = section.querySelector('[data-faq-title]');
			var items = section.querySelectorAll('[data-faq-item]');
			var triggers = section.querySelectorAll('.faq__trigger');

			/* Entrance: title + staggered items when section enters view (GSAP) */
			if (!reducedMotion && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
				gsap.registerPlugin(ScrollTrigger);
				gsap.set([titleEl, items], { opacity: 0, y: 24 });
				var tl = gsap.timeline({
					scrollTrigger: {
						trigger: section,
						start: 'top 85%',
						toggleActions: 'play none none none',
					},
					defaults: { duration: 0.5, ease: 'power2.out' },
				});
				if (titleEl) tl.to(titleEl, { opacity: 1, y: 0 }, 0);
				tl.to(items, { opacity: 1, y: 0, stagger: 0.06 }, titleEl ? 0.08 : 0);
			} else if (titleEl || items.length) {
				[titleEl].concat(Array.prototype.slice.call(items)).filter(Boolean).forEach(function(el) { el.style.opacity = '1'; el.style.transform = 'none'; });
			}

			/* Accordion: GSAP choreographed open/close */
			triggers.forEach(function(btn) {
				btn.addEventListener('click', function() {
					var expanded = btn.getAttribute('aria-expanded') === 'true';
					var panelId = btn.getAttribute('aria-controls');
					var panel = panelId ? document.getElementById(panelId) : null;
					var item = btn.closest('.faq__item');
					if (!item) return;

					var answerWrap = panel ? panel.querySelector('.faq__answer-wrap') : null;
					var chevron = btn.querySelector('.faq__chevron');

					if (expanded) {
						btn.setAttribute('aria-expanded', 'false');
						item.classList.remove('faq__item--open');
						if (panel && !reducedMotion && typeof gsap !== 'undefined') {
							var closeTl = gsap.timeline({ onComplete: function() {
								panel.hidden = true;
								panel.style.height = '';
								if (answerWrap) { answerWrap.style.transform = ''; answerWrap.style.opacity = ''; }
							}});
							if (answerWrap) closeTl.to(answerWrap, { y: -6, opacity: 0, duration: 0.18, ease: 'power2.in' }, 0);
							if (chevron) closeTl.to(chevron, { rotation: 0, duration: 0.24, ease: 'back.in(1.4)' }, 0);
							closeTl.to(panel, { height: 0, duration: 0.3, ease: 'power3.in', overflow: 'hidden' }, 0);
						} else if (panel) {
							panel.hidden = true;
						}
					} else {
						/* Close others */
						section.querySelectorAll('.faq__item--open').forEach(function(openItem) {
							var openBtn = openItem.querySelector('.faq__trigger');
							var openPanelId = openBtn && openBtn.getAttribute('aria-controls');
							var openPanel = openPanelId ? document.getElementById(openPanelId) : null;
							var openWrap = openPanel ? openPanel.querySelector('.faq__answer-wrap') : null;
							var openChevron = openBtn ? openBtn.querySelector('.faq__chevron') : null;
							if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
							openItem.classList.remove('faq__item--open');
							if (openPanel && typeof gsap !== 'undefined') gsap.killTweensOf([openPanel, openWrap, openChevron]);
							if (openPanel) { openPanel.hidden = true; openPanel.style.height = ''; }
							if (openWrap) { openWrap.style.transform = ''; openWrap.style.opacity = ''; }
							if (openChevron) openChevron.style.transform = '';
						});
						btn.setAttribute('aria-expanded', 'true');
						item.classList.add('faq__item--open');
						if (panel) {
							panel.hidden = false;
							panel.style.overflow = 'hidden';
							if (!reducedMotion && typeof gsap !== 'undefined') {
								gsap.set(panel, { height: 'auto' });
								var endHeight = panel.offsetHeight;
								gsap.set(panel, { height: 0 });
								if (answerWrap) gsap.set(answerWrap, { y: 10, opacity: 0 });
								var openTl = gsap.timeline({
									defaults: { ease: 'none', overwrite: 'auto' },
									onComplete: function() {
										panel.style.height = '';
										panel.style.overflow = '';
										if (answerWrap) { answerWrap.style.transform = ''; answerWrap.style.opacity = ''; }
									}
								});
								openTl.to(panel, { height: endHeight, duration: 0.42, ease: 'expo.out' }, 0);
								if (answerWrap) openTl.to(answerWrap, { y: 0, opacity: 1, duration: 0.36, ease: 'expo.out', delay: 0.06 }, 0);
								if (chevron) openTl.to(chevron, { rotation: 180, duration: 0.32, ease: 'back.out(1.2)' }, 0);
							}
						}
					}
				});
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

<style>
/* Rotate chevron when open (down → up); fallback when GSAP not used */
.faq__item--open .faq__chevron {
	transform: rotate(180deg);
}
/* Smooth transition for reduced-motion / no-JS (GSAP uses inline transform when available) */
.faq__chevron {
	transform-origin: center center;
}
</style>
