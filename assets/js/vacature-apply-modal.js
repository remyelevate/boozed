(function() {
	'use strict';

	var modal = document.getElementById('vacature-sollicitatie-modal');
	if (!modal) {
		return;
	}

	var closeTriggers = modal.querySelectorAll('[data-vacature-sollicitatie-close]');
	var lastFocus = null;

	function lockBodyScroll() {
		document.documentElement.classList.add('vacature-sollicitatie-open');
	}

	function unlockBodyScroll() {
		document.documentElement.classList.remove('vacature-sollicitatie-open');
	}

	function isOpen() {
		return modal.classList.contains('is-open');
	}

	function isSamePageSollicitatieLink(anchor) {
		if (!anchor || anchor.tagName !== 'A') {
			return false;
		}
		if (anchor.getAttribute('data-vacature-sollicitatie-ignore') === '1') {
			return false;
		}
		var hrefAttr = anchor.getAttribute('href');
		if (!hrefAttr) {
			return false;
		}
		try {
			var u = new URL(anchor.href, window.location.href);
			if (u.hash !== '#solliciteren') {
				return false;
			}
			return u.pathname === window.location.pathname && u.search === window.location.search;
		} catch (e) {
			return false;
		}
	}

	function firstFocusable() {
		var panel = modal.querySelector('.vacature-sollicitatie-modal__panel');
		var sel = 'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
		var root = modal.querySelector('.vacature-sollicitatie-modal__cf7') || panel || modal;
		var el = root.querySelector(sel);
		return el || (panel ? panel.querySelector(sel) : null);
	}

	function openModal() {
		if (isOpen()) {
			return;
		}
		lastFocus = document.activeElement;
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		lockBodyScroll();
		var first = firstFocusable();
		if (first) {
			first.focus();
		}
	}

	function closeModal() {
		if (!isOpen()) {
			return;
		}
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		unlockBodyScroll();
		if (lastFocus && typeof lastFocus.focus === 'function') {
			try {
				lastFocus.focus();
			} catch (err) {}
		}
	}

	function stripHashFromUrl() {
		if (window.location.hash === '#solliciteren') {
			var path = window.location.pathname + window.location.search;
			if (window.history && window.history.replaceState) {
				window.history.replaceState(null, '', path);
			}
		}
	}

	document.addEventListener('click', function(e) {
		var a = e.target.closest('a');
		if (!a) {
			return;
		}
		if (a.hasAttribute('data-vacature-sollicitatie-open')) {
			e.preventDefault();
			e.stopPropagation();
			stripHashFromUrl();
			openModal();
			return;
		}
		if (!isSamePageSollicitatieLink(a)) {
			return;
		}
		e.preventDefault();
		stripHashFromUrl();
		openModal();
	}, true);

	window.addEventListener('hashchange', function() {
		if (window.location.hash === '#solliciteren') {
			openModal();
			stripHashFromUrl();
		}
	});

	if (window.location.hash === '#solliciteren') {
		openModal();
		stripHashFromUrl();
	}

	closeTriggers.forEach(function(btn) {
		btn.addEventListener('click', function() {
			closeModal();
		});
	});

	document.addEventListener('keydown', function(e) {
		if (e.key !== 'Escape') {
			return;
		}
		if (!isOpen()) {
			return;
		}
		closeModal();
	});
})();
