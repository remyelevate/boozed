<?php

/**
 * Workday section
 * Two columns: left = title, intro, schedule list; right = dark panel whose content changes on slot hover.
 * Each time slot has its own right-panel content. Hovering a slot swaps the text; leaving resets to the default.
 */

$title     = function_exists('get_sub_field') ? (string) get_sub_field('workday_title') : '';
$intro     = function_exists('get_sub_field') ? (string) get_sub_field('workday_intro') : '';
$slots_raw = function_exists('get_sub_field') ? (array) get_sub_field('workday_slots') : [];

if ($title === '') {
	$title = 'Zo ziet jouw dag eruit!';
}
if ($intro === '') {
	$intro = 'Een stand die iedereen op de beurs jaloers maakt? Pop-up store in hartje stad? Of een secret party verstopt in een festival? Je kunt het zo gek niet bedenken en wij maken het voor je. Groot, intiem, zakelijk of feestelijk.';
}
if (empty($slots_raw)) {
	$slots_raw = [
		[ 'label' => '09:00 - 12:00', 'is_highlighted' => false, 'content' => '' ],
		[ 'label' => '12:00 - 12:30', 'is_highlighted' => true,  'content' => '<p>Je werkt meestal tussen 07:00 en 17:00 uur, maar in de eventwereld kan het soms nét even anders lopen. Soms moet een order af, moet er extra ruimte gecreëerd worden bij drukte of moeten leveranciersmaterialen nog verwerkt worden.</p><p>Je draait eens per 6 weken mee in onze nooddienst: je bent dan bereikbaar in het weekend voor noodgevallen.</p>' ],
		[ 'label' => '12:30 - 15:00', 'is_highlighted' => false, 'content' => '' ],
		[ 'label' => '15:00 - 17:00', 'is_highlighted' => false, 'content' => '' ],
	];
}

$slots = [];
$default_index = 0;
foreach ($slots_raw as $item) {
	$label = isset($item['label']) ? trim((string) $item['label']) : '';
	if ($label === '') {
		continue;
	}
	$content     = isset($item['content']) ? (string) $item['content'] : '';
	$highlighted = ! empty($item['is_highlighted']);
	if ($highlighted) {
		$default_index = count($slots);
	}
	$slots[] = [
		'label'          => $label,
		'is_highlighted' => $highlighted,
		'content'        => $content,
	];
}

if (empty($slots)) {
	return;
}

$phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';

$slots_json = wp_json_encode(array_map(function ($s) {
	return [ 'content' => wpautop($s['content']) ];
}, $slots));
?>
<section class="workday bg-brand-white pb-10 md:pb-section-y" data-workday-default="<?php echo (int) $default_index; ?>">
	<script type="application/json" class="workday-data"><?php echo $slots_json; ?></script>

	<div class="workday__inner max-w-section mx-auto">
		<div class="workday__grid grid grid-cols-1 lg:grid-cols-2 lg:items-stretch lg:gap-0">
			<div class="workday__left pl-4 pt-10 md:pl-section-x md:pt-section-y">
				<h4 class="workday__title font-heading font-bold text-h4 md:text-h4-lg text-brand-black mb-4 md:mb-6 px-section-x"><?php echo esc_html($title); ?></h4>
				<p class="workday__intro font-body text-body md:text-body-md text-brand-black mb-8 md:mb-10 px-section-x"><?php echo esc_html($intro); ?></p>
				<ul class="workday__slots list-none p-0 m-0 flex flex-col" aria-label="<?php esc_attr_e('Dagindeling', 'boozed'); ?>">
					<?php foreach ($slots as $i => $slot) :
						$is_active = ($i === $default_index);
						$is_first  = ($i === 0);
						$is_last   = ($i === count($slots) - 1);
						$border_classes = 'border-t border-brand-border';
						if ($is_last) {
							$border_classes .= ' border-b';
						}
					?>
						<li class="workday__slot flex items-center justify-between gap-4 px-6 py-10 md:px-section-x <?php echo $border_classes; ?> font-heading font-bold text-body-md md:text-body-lg cursor-pointer transition-all duration-200 <?php echo $is_active ? 'workday__slot--active bg-brand-purple text-brand-white !border-transparent' : 'text-brand-black'; ?>"
						    data-slot-index="<?php echo (int) $i; ?>">
							<span><?php echo esc_html($slot['label']); ?></span>
							<span class="workday__chevron shrink-0 text-brand-coral transition-colors duration-200" aria-hidden="true"><?php echo $phosphor_chevron_right; ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="workday__right bg-brand-indigo text-brand-white flex items-center px-6 py-10 md:px-16 md:py-12 lg:px-20">
				<div class="workday__right-content font-body text-body md:text-body-md leading-[1.5] prose prose-invert max-w-none transition-opacity duration-200">
					<?php echo wp_kses_post(wpautop($slots[$default_index]['content'])); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if (empty($GLOBALS['boozed_workday_script_printed'])) : $GLOBALS['boozed_workday_script_printed'] = true; ?>
<script>
(function() {
	function init() {
		document.querySelectorAll('.workday').forEach(function(section) {
			var slotEls = section.querySelectorAll('.workday__slot');
			var contentEl = section.querySelector('.workday__right-content');
			if (!slotEls.length || !contentEl) return;

			var defaultIndex = parseInt(section.getAttribute('data-workday-default'), 10) || 0;
			var currentIndex = defaultIndex;

			var slotsData = [];
			var dataScript = section.querySelector('script.workday-data');
			if (dataScript) {
				try { slotsData = JSON.parse(dataScript.textContent); } catch (e) {}
			}

			function setActive(idx) {
				if (idx === currentIndex) return;

				slotEls.forEach(function(el, j) {
					if (j === idx) {
						el.classList.add('workday__slot--active', 'bg-brand-purple', 'text-brand-white', '!border-transparent');
						el.classList.remove('text-brand-black');
					} else {
						el.classList.remove('workday__slot--active', 'bg-brand-purple', 'text-brand-white', '!border-transparent');
						el.classList.add('text-brand-black');
					}
				});

				if (slotsData[idx] && slotsData[idx].content) {
					contentEl.style.opacity = '0';
					setTimeout(function() {
						contentEl.innerHTML = slotsData[idx].content;
						contentEl.style.opacity = '1';
					}, 150);
				}

				currentIndex = idx;
			}

			slotEls.forEach(function(el, idx) {
				el.addEventListener('mouseenter', function() {
					setActive(idx);
				});
				el.addEventListener('click', function() {
					setActive(idx);
				});
			});

			var slotsWrap = section.querySelector('.workday__slots');
			if (slotsWrap) {
				slotsWrap.addEventListener('mouseleave', function() {
					setActive(defaultIndex);
				});
			}
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
.workday__right-content p {
	margin: 0 0 1em;
}
.workday__right-content p:last-child {
	margin-bottom: 0;
}
.workday__slot--active .workday__chevron {
	color: #FFFFFF;
}
</style>
