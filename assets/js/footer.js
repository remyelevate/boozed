/**
 * Footer CTA – arrow Lottie oneshot when banner comes into view (with small delay)
 */
(function () {
  var DELAY_MS = 400;

  function init() {
    var banner = document.querySelector('.site-footer__cta-banner');
    var arrowContainer = banner ? banner.querySelector('.site-footer__cta-lottie') : null;
    var lottieUrl = banner ? banner.getAttribute('data-footer-cta-lottie') : '';
    if (!banner || !arrowContainer || !lottieUrl || typeof lottie === 'undefined') return;

    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reducedMotion) return;

    var anim = null;
    var playTimeout = null;

    function playOneshot() {
      try {
        if (!anim) {
          anim = lottie.loadAnimation({
            container: arrowContainer,
            renderer: 'svg',
            loop: false,
            autoplay: false,
            path: lottieUrl
          });
          anim.addEventListener('complete', function () {
            anim.goToAndStop(anim.totalFrames - 1, true);
          });
        }
        anim.goToAndPlay(0, true);
      } catch (e) {}
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          if (playTimeout) clearTimeout(playTimeout);
          playTimeout = setTimeout(playOneshot, DELAY_MS);
        });
      },
      { threshold: 0.2, rootMargin: '0px 0px 50px 0px' }
    );

    observer.observe(banner);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
