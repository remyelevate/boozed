(function () {
  'use strict';

  if (typeof LocomotiveScroll === 'undefined') return;

  var scroll = new LocomotiveScroll({
    autoStart: true,
    scrollCallback: function (e) {
      // Expose scroll position for header and others (Lenis updates native scroll, but we can still use this)
      if (window.dispatchEvent && e && typeof e.scroll === 'number') {
        try {
          window.dispatchEvent(new CustomEvent('locomotiveScroll', { detail: { scroll: e.scroll } }));
        } catch (err) {}
      }
    },
  });

  // Integrate GSAP ScrollTrigger with Lenis when both are present
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined' && scroll.lenisInstance) {
    scroll.lenisInstance.on('scroll', ScrollTrigger.update);
    ScrollTrigger.scrollerProxy(document.documentElement, {
      scrollTop: function () {
        return scroll.lenisInstance ? scroll.lenisInstance.scroll : 0;
      },
      getBoundingClientRect: function () {
        return document.documentElement.getBoundingClientRect();
      },
    });
  }

  window.boozedLocomotiveScroll = scroll;
})();
