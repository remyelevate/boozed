(function () {
  'use strict';

  var STORAGE_KEY = 'boozed-page-transition';
  var overlay = document.getElementById('page-transition-overlay');
  var videoForward = document.getElementById('page-transition-forward');
  var videoReverse = document.getElementById('page-transition-reverse');

  if (!overlay || !videoForward || !videoReverse) return;

  function isInternalLink(link) {
    var href = link.getAttribute('href');
    if (!href || href === '#') return false;
    if (href.indexOf('tel:') === 0 || href.indexOf('mailto:') === 0 || href.indexOf('javascript:') === 0) return false;
    if (link.target === '_blank' || link.hasAttribute('data-no-transition')) return false;
    if (link.hasAttribute('data-vacature-sollicitatie-open')) return false;
    if (href.charAt(0) === '#') return false;
    try {
      var u = new URL(link.href, window.location.href);
      if (u.hash && u.pathname === window.location.pathname && u.search === window.location.search) return false;
      var linkHost = link.hostname || '';
      return linkHost === window.location.hostname;
    } catch (e) {
      return false;
    }
  }

  function showOverlay() {
    overlay.classList.add('is-active');
    overlay.setAttribute('aria-hidden', 'false');
  }

  function hideOverlay() {
    overlay.classList.add('is-hiding');
    overlay.classList.remove('is-active');
    overlay.setAttribute('aria-hidden', 'true');
    videoForward.pause();
    videoForward.currentTime = 0;
    videoReverse.pause();
    videoReverse.currentTime = 0;
    try {
      document.documentElement.classList.remove('has-page-transition-pending');
    } catch (e) {}
    requestAnimationFrame(function () {
      overlay.classList.remove('is-hiding');
    });
  }

  function playForwardThenNavigate(href) {
    if (overlay.classList.contains('is-active')) return;
    showOverlay();
    videoReverse.classList.add('u-hidden');
    videoForward.classList.remove('u-hidden');
    videoReverse.pause();
    videoReverse.currentTime = 0;
    videoForward.currentTime = 0;

    function onEnded() {
      videoForward.removeEventListener('ended', onEnded);
      try {
        sessionStorage.setItem(STORAGE_KEY, '1');
      } catch (e) {}
      window.location.href = href;
    }

    videoForward.playbackRate = 3;
    videoForward.addEventListener('ended', onEnded, { once: true });
    videoForward.play().catch(function () {
      videoForward.removeEventListener('ended', onEnded);
      try {
        sessionStorage.setItem(STORAGE_KEY, '1');
      } catch (e) {}
      window.location.href = href;
    });
  }

  function playReverseThenReveal() {
    try {
      if (sessionStorage.getItem(STORAGE_KEY) !== '1') return;
    } catch (e) {
      return;
    }
    showOverlay();
    videoForward.classList.add('u-hidden');
    videoReverse.classList.remove('u-hidden');
    videoForward.pause();
    videoForward.currentTime = 0;
    videoReverse.currentTime = 0;

    videoReverse.addEventListener('playing', function () {
      document.documentElement.classList.remove('has-page-transition-pending');
    }, { once: true });

    function onEnded() {
      videoReverse.removeEventListener('ended', onEnded);
      try {
        sessionStorage.removeItem(STORAGE_KEY);
      } catch (e) {}
      requestAnimationFrame(function () {
        requestAnimationFrame(hideOverlay);
      });
    }

    videoReverse.playbackRate = 3;
    videoReverse.addEventListener('ended', onEnded, { once: true });
    videoReverse.play().catch(function () {
      videoReverse.removeEventListener('ended', onEnded);
      try {
        sessionStorage.removeItem(STORAGE_KEY);
      } catch (e) {}
      hideOverlay();
    });
  }

  document.addEventListener('click', function (e) {
    var link = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!link || !isInternalLink(link)) return;
    e.preventDefault();
    playForwardThenNavigate(link.href);
  }, true);

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', playReverseThenReveal);
  } else {
    playReverseThenReveal();
  }

  // BFCache (browser back/forward): DOMContentLoaded does not run again. A page
  // frozen mid leave-transition still has the overlay active, or a page frozen
  // before the reverse "playing" handler ran can keep has-page-transition-pending
  // and the solid indigo mask — clear both so content is visible.
  window.addEventListener(
    'pageshow',
    function (ev) {
      if (!ev.persisted) return;
      var stuckPending = document.documentElement.classList.contains('has-page-transition-pending');
      var stuckOverlay = overlay.classList.contains('is-active');
      if (!stuckPending && !stuckOverlay) return;
      try {
        sessionStorage.removeItem(STORAGE_KEY);
      } catch (e) {}
      hideOverlay();
    },
    false
  );
})();
