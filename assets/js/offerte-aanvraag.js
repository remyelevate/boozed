(function() {
	'use strict';

	var section = document.querySelector('.section-offerte-aanvraag');
	if (!section) return;

	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var form = section.querySelector('.section-offerte-aanvraag__form');
	var panels = section.querySelectorAll('.section-offerte-aanvraag__panel');
	var steps = section.querySelectorAll('.section-offerte-aanvraag__step');
	var barSegmentFills = section.querySelectorAll('.section-offerte-aanvraag__bar-segment-fill');
	var stepCircles = section.querySelectorAll('.section-offerte-aanvraag__step-circle');
	var stepLabels = section.querySelectorAll('.section-offerte-aanvraag__step-label');
	var imageWraps = section.querySelectorAll('.section-offerte-aanvraag__image-wrap');
	var nextBtns = section.querySelectorAll('.js-offerte-next');
	var backBtns = section.querySelectorAll('.js-offerte-back');
	var submitBtn = section.querySelector('.js-offerte-submit');
	var fileInput = section.querySelector('.js-offerte-file-input');
	var fileLabel = section.querySelector('.js-offerte-file-label');
	var messageEl = section.querySelector('.section-offerte-aanvraag__message');

	var currentStep = 1;
	var totalSteps = 4;
	var ajaxUrl = section.getAttribute('data-offerte-ajax') || '';

	function setStep(step) {
		step = Math.max(1, Math.min(totalSteps, step));
		var prevStep = currentStep;
		currentStep = step;

		// Panels: show only current
		panels.forEach(function(panel) {
			var n = parseInt(panel.getAttribute('data-panel'), 10);
			if (n === step) {
				panel.classList.remove('hidden');
				panel.setAttribute('aria-hidden', 'false');
				if (reducedMotion && typeof gsap !== 'undefined') gsap.set(panel, { x: 0, opacity: 1 });
			} else {
				panel.classList.add('hidden');
				panel.setAttribute('aria-hidden', 'true');
			}
		});

		// Progress: active and completed state
		steps.forEach(function(stepEl, idx) {
			var n = idx + 1;
			var circle = stepCircles[idx];
			var label = stepLabels[idx];
			if (!circle) return;
			if (n < step) {
				circle.classList.add('!bg-brand-purple', '!border-brand-purple', '!text-brand-white');
				circle.classList.remove('bg-brand-white', 'border-brand-border', 'text-gray-400');
				circle.setAttribute('aria-current', 'false');
				if (label) label.classList.remove('text-gray-400'), label.classList.add('text-brand-purple');
			} else if (n === step) {
				circle.classList.add('!bg-brand-purple', '!border-brand-purple', '!text-brand-white');
				circle.classList.remove('bg-brand-white', 'border-brand-border', 'text-gray-400');
				circle.setAttribute('aria-current', 'step');
				if (label) label.classList.remove('text-gray-400'), label.classList.add('text-brand-purple');
			} else {
				circle.classList.remove('!bg-brand-purple', '!border-brand-purple', '!text-brand-white');
				circle.classList.add('bg-brand-white', 'border-brand-border', 'text-gray-400');
				circle.setAttribute('aria-current', 'false');
				if (label) label.classList.add('text-gray-400'), label.classList.remove('text-brand-purple');
			}
		});

		// Progress bar: animate fill in each segment (fill-up effect)
		barSegmentFills.forEach(function(fill, idx) {
			var n = idx + 1;
			var filled = n <= step;
			var scale = filled ? 1 : 0;
			if (typeof gsap !== 'undefined' && !reducedMotion) {
				gsap.to(fill, { scaleX: scale, duration: 0.35, ease: 'power2.out', transformOrigin: 'left' });
			} else {
				fill.style.transform = 'scaleX(' + scale + ')';
				fill.style.transformOrigin = 'left';
			}
		});

		// Right column image: show image for current step
		imageWraps.forEach(function(wrap) {
			var n = parseInt(wrap.getAttribute('data-image-step'), 10);
			wrap.classList.toggle('hidden', n !== step);
		});
	}

	function goNext() {
		var panel = section.querySelector('.section-offerte-aanvraag__panel[data-panel="' + currentStep + '"]');
		var nextPanel = section.querySelector('.section-offerte-aanvraag__panel[data-panel="' + (currentStep + 1) + '"]');
		if (currentStep >= totalSteps) return;

		// Validate step 2 and 3 before proceeding
		if (currentStep === 2) {
			var required = panel.querySelectorAll('[required]');
			var valid = true;
			required.forEach(function(input) {
				if (!input.value.trim()) valid = false;
			});
			if (!valid) {
				form.reportValidity();
				return;
			}
		}

		if (reducedMotion || typeof gsap === 'undefined') {
			setStep(currentStep + 1);
			return;
		}

		var outX = -50;
		var inX = 50;
		var duration = 0.35;
		var ease = 'power2.inOut';

		gsap.to(panel, { x: outX, opacity: 0, duration: duration, ease: ease, onComplete: function() {
			setStep(currentStep + 1);
			var enterPanel = section.querySelector('.section-offerte-aanvraag__panel[data-panel="' + currentStep + '"]');
			gsap.set(enterPanel, { x: inX, opacity: 0 });
			gsap.to(enterPanel, { x: 0, opacity: 1, duration: duration, ease: ease });
			var fields = enterPanel.querySelectorAll('.section-offerte-aanvraag__title, .section-offerte-aanvraag__desc, .space-y-4 > *, .flex.flex-wrap');
			if (fields.length) {
				gsap.fromTo(fields, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.25, stagger: 0.05, delay: duration * 0.5, ease: 'power2.out' });
			}
		}});
	}

	function goBack() {
		var panel = section.querySelector('.section-offerte-aanvraag__panel[data-panel="' + currentStep + '"]');
		if (currentStep <= 1) return;

		if (reducedMotion || typeof gsap === 'undefined') {
			setStep(currentStep - 1);
			return;
		}

		var outX = 50;
		var inX = -50;
		var duration = 0.35;
		var ease = 'power2.inOut';

		gsap.to(panel, { x: outX, opacity: 0, duration: duration, ease: ease, onComplete: function() {
			setStep(currentStep - 1);
			var enterPanel = section.querySelector('.section-offerte-aanvraag__panel[data-panel="' + currentStep + '"]');
			gsap.set(enterPanel, { x: inX, opacity: 0 });
			gsap.to(enterPanel, { x: 0, opacity: 1, duration: duration, ease: ease });
		}});
	}

	function showMessage(text, isError) {
		if (!messageEl) return;
		messageEl.textContent = text;
		messageEl.classList.remove('hidden', 'text-red-600', 'text-brand-purple');
		messageEl.classList.add(isError ? 'text-red-600' : 'text-brand-purple');
		messageEl.setAttribute('role', 'alert');
	}

	function hideMessage() {
		if (messageEl) messageEl.classList.add('hidden');
	}

	function handleSubmit(e) {
		e.preventDefault();
		if (!ajaxUrl) {
			showMessage('Formulier is niet geconfigureerd.', true);
			return;
		}

		var formData = new FormData(form);
		formData.set('action', 'boozed_offerte_aanvraag_submit');

		if (submitBtn) {
			submitBtn.disabled = true;
			submitBtn.textContent = 'Bezig met versturen…';
		}
		hideMessage();

		fetch(ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin',
		})
			.then(function(res) { return res.json(); })
			.then(function(data) {
				if (data.success) {
					showMessage('Bedankt! We hebben je aanvraag ontvangen en nemen zo snel mogelijk contact op.', false);
					form.reset();
					setStep(1);
					if (fileLabel) fileLabel.textContent = 'Klik of sleep je bestanden naar dit veld';
				} else {
					var msg = (data.data && data.data.message) ? data.data.message : 'Er is iets misgegaan. Probeer het later opnieuw.';
					showMessage(msg, true);
				}
			})
			.catch(function() {
				showMessage('Er is iets misgegaan. Probeer het later opnieuw.', true);
			})
			.finally(function() {
				if (submitBtn) {
					submitBtn.disabled = false;
					submitBtn.innerHTML = 'Versturen &gt; <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
				}
			});
	}

	nextBtns.forEach(function(btn) { btn.addEventListener('click', goNext); });
	backBtns.forEach(function(btn) { btn.addEventListener('click', goBack); });
	if (form) form.addEventListener('submit', handleSubmit);

	if (fileInput && fileLabel) {
		fileInput.addEventListener('change', function() {
			var files = fileInput.files;
			if (files && files.length > 0) {
				fileLabel.textContent = files.length === 1 ? files[0].name : files.length + ' bestanden gekozen';
			} else {
				fileLabel.textContent = 'Klik of sleep je bestanden naar dit veld';
			}
		});
		// Drag and drop
		var dropZone = fileInput.closest('label');
		if (dropZone) {
			['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(ev) {
				dropZone.addEventListener(ev, function(e) {
					e.preventDefault();
					e.stopPropagation();
				});
			});
			dropZone.addEventListener('drop', function(e) {
				var files = e.dataTransfer && e.dataTransfer.files;
				if (files && files.length) {
					fileInput.files = files;
					fileLabel.textContent = files.length === 1 ? files[0].name : files.length + ' bestanden gekozen';
				}
			});
		}
	}

	setStep(1);
})();
