(function () {
  'use strict';

  var header = document.getElementById('site-header');
  var mobileMenu = document.getElementById('mobile-menu');
  var toggleBtn = header && header.querySelector('.site-header__toggle');

  if (!header) return;

  var scrolled = false;
  var hovered = false;
  var hasHeroFirst = false;

  // Transparent header when first section is hero, page-header, or thank-you (dark background, white text)
  function checkHeroFirst() {
    var main = document.getElementById('main');
    if (!main) {
      hasHeroFirst = false;
      return;
    }
    var firstSection = main.querySelector('section');
    hasHeroFirst = !!(firstSection && (
      firstSection.classList.contains('hero') ||
      firstSection.classList.contains('page-header') ||
      firstSection.classList.contains('thank-you')
    ));
  }

  // When first section is a light page-header, show backdrop at top so logo/nav are visible (white on white would be invisible)
  function isFirstSectionLightPageHeader() {
    var main = document.getElementById('main');
    if (!main) return false;
    var firstSection = main.querySelector('section');
    return !!(firstSection &&
      firstSection.classList.contains('page-header') &&
      firstSection.getAttribute('data-page-header-background') === 'light');
  }

  function updateBackdrop() {
    var show = scrolled || hovered || !hasHeroFirst || (hasHeroFirst && !scrolled && !hovered && isFirstSectionLightPageHeader());
    header.classList.toggle('has-backdrop', show);
  }

  function onScroll() {
    scrolled = window.scrollY > 0;
    updateBackdrop();
  }

  function onMouseEnter() {
    hovered = true;
    updateBackdrop();
  }

  function onMouseLeave() {
    hovered = false;
    updateBackdrop();
  }

  checkHeroFirst();
  window.addEventListener('scroll', onScroll, { passive: true });
  header.addEventListener('mouseenter', onMouseEnter);
  header.addEventListener('mouseleave', onMouseLeave);
  onScroll();

  (function () {
    var nav = header.querySelector('.site-header__nav');
    if (!nav) return;
    var parents = nav.querySelectorAll('.menu-item-has-children, .dropdown-trigger, .has-dropdown');
    parents.forEach(function (parent) {
      var link = parent.querySelector(':scope > a');
      if (!link) return;
      link.setAttribute('aria-haspopup', 'true');
    });
  })();

  (function () {
    var megaTrigger = header.querySelector('.has-mega-menu');
    var megaPanel = document.getElementById('header-mega-panel');
    var heroOverlay = document.getElementById('hero-mega-menu-overlay');
    var nav = header.querySelector('.site-header__nav');
    var allNavItems = nav ? nav.querySelectorAll('.nav-menu > li') : [];
    var otherNavItems = [];
    
    // Collect non-mega menu items
    allNavItems.forEach(function(item) {
      if (!item.classList.contains('has-mega-menu')) {
        otherNavItems.push(item);
      }
    });
    
    if (!megaTrigger || !megaPanel) return;

    var closeTimeout = null;
    var isOpen = false;

    function openMega() {
      if (closeTimeout) {
        clearTimeout(closeTimeout);
        closeTimeout = null;
      }
      if (isOpen) return;
      isOpen = true;
      
      document.body.classList.add('header-mega-open');
      megaPanel.classList.add('is-open');
      megaPanel.setAttribute('aria-hidden', 'false');
    }
    
    function closeMega() {
      if (!isOpen) return;
      isOpen = false;
      
      document.body.classList.remove('header-mega-open');
      megaPanel.classList.remove('is-open');
      megaPanel.setAttribute('aria-hidden', 'true');
    }
    
    function closeMegaDelayed() {
      if (closeTimeout) {
        clearTimeout(closeTimeout);
      }
      // Small delay to allow moving between trigger and panel
      closeTimeout = setTimeout(function() {
        closeMega();
      }, 100);
    }

    function onMegaTriggerEnter() {
      openMega();
    }
    
    function onMegaTriggerLeave(e) {
      var related = e.relatedTarget;
      // If moving to the panel, keep it open
      if (related && megaPanel.contains(related)) {
        return;
      }
      closeMegaDelayed();
    }
    
    function onPanelEnter() {
      if (closeTimeout) {
        clearTimeout(closeTimeout);
        closeTimeout = null;
      }
    }
    
    function onPanelLeave(e) {
      var related = e.relatedTarget;
      // If moving back to trigger, keep it open
      if (related && megaTrigger.contains(related)) {
        return;
      }
      closeMegaDelayed();
    }
    
    // Close mega menu when hovering other nav items
    function onOtherNavEnter() {
      closeMega();
    }

    megaTrigger.addEventListener('mouseenter', onMegaTriggerEnter);
    megaTrigger.addEventListener('mouseleave', onMegaTriggerLeave);
    megaPanel.addEventListener('mouseenter', onPanelEnter);
    megaPanel.addEventListener('mouseleave', onPanelLeave);
    
    // Add listeners to other nav items to close mega menu
    otherNavItems.forEach(function(item) {
      item.addEventListener('mouseenter', onOtherNavEnter);
    });
    
    // Also close when mouse leaves the header entirely
    header.addEventListener('mouseleave', function(e) {
      var related = e.relatedTarget;
      if (!related || !megaPanel.contains(related)) {
        closeMegaDelayed();
      }
    });
  })();

  if (toggleBtn && mobileMenu) {
    var lottieWrapper = mobileMenu.querySelector('.mobile-menu__lottie');
    var lottieContainer = mobileMenu.querySelector('.mobile-menu__lottie-inner');
    var lottieUrl = lottieWrapper ? lottieWrapper.getAttribute('data-mobile-menu-lottie') : '';
    var lottieAnim = null;
    var menuTl = null;

    function playMenuOpen() {
      var content = mobileMenu.querySelector('.mobile-menu__content');
      var navItems = mobileMenu.querySelectorAll('.mobile-menu__nav ul > li');
      var actions = mobileMenu.querySelectorAll('.mobile-menu__action');
      if (lottieContainer && lottieUrl && typeof lottie !== 'undefined') {
        if (!lottieAnim) {
          lottieAnim = lottie.loadAnimation({
            container: lottieContainer,
            renderer: 'svg',
            loop: false,
            autoplay: false,
            path: lottieUrl
          });
          lottieAnim.addEventListener('complete', function () {
            lottieAnim.goToAndStop(lottieAnim.totalFrames - 1, true);
          });
        }
        lottieAnim.goToAndPlay(0, true);
      }
      if (menuTl) menuTl.kill();
      if (typeof gsap === 'undefined' || !content) return;
      menuTl = gsap.timeline({ defaults: { ease: 'power2.out' } });
      menuTl
        .fromTo(content, { opacity: 0 }, { opacity: 1, duration: 0.4 })
        .fromTo(navItems, { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.4, stagger: 0.08 }, '-=0.3')
        .fromTo(actions, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.35, stagger: 0.06 }, '-=0.15');
    }

    function resetMenu() {
      if (menuTl) menuTl.kill();
      var content = mobileMenu.querySelector('.mobile-menu__content');
      var navItems = mobileMenu.querySelectorAll('.mobile-menu__nav ul > li');
      var actions = mobileMenu.querySelectorAll('.mobile-menu__action');
      if (content && typeof gsap !== 'undefined') {
        gsap.set([content, navItems, actions], { clearProps: 'opacity,y' });
      }
    }

    toggleBtn.addEventListener('click', function () {
      var open = mobileMenu.classList.toggle('is-open');
      toggleBtn.setAttribute('aria-expanded', open);
      toggleBtn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      mobileMenu.setAttribute('aria-hidden', !open);
      document.body.style.overflow = open ? 'hidden' : '';
      if (open) {
        playMenuOpen();
      } else {
        resetMenu();
      }
    });

    mobileMenu.addEventListener('click', function (e) {
      if (e.target === mobileMenu || e.target.closest('a')) {
        mobileMenu.classList.remove('is-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.setAttribute('aria-label', 'Open menu');
        mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        resetMenu();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
        mobileMenu.classList.remove('is-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.setAttribute('aria-label', 'Open menu');
        mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        resetMenu();
      }
    });

    (function initAccordions() {
      var triggers = mobileMenu.querySelectorAll('.mobile-menu__accordion-trigger');
      var useGsap = typeof gsap !== 'undefined';

      function openAccordion(btn, submenu, accordion) {
        submenu.hidden = false;
        submenu.setAttribute('aria-hidden', 'false');
        accordion.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');

        if (useGsap) {
          var items = submenu.querySelectorAll('.mobile-menu__submenu-item');
          submenu.style.overflow = 'hidden';
          var fullHeight = submenu.offsetHeight;
          gsap.set(submenu, { height: 0, opacity: 0 });
          gsap.set(items, { opacity: 0, y: -8 });
          gsap.to(submenu, {
            height: fullHeight,
            opacity: 1,
            duration: 0.35,
            ease: 'power2.out',
            onComplete: function () {
              submenu.style.height = '';
              submenu.style.overflow = '';
            }
          });
          gsap.to(items, {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.05,
            ease: 'power2.out',
            delay: 0.05
          });
        }
      }

      function closeAccordion(btn, submenu, accordion) {
        if (useGsap) {
          var items = submenu.querySelectorAll('.mobile-menu__submenu-item');
          var startHeight = submenu.offsetHeight;
          submenu.style.overflow = 'hidden';
          submenu.style.height = startHeight + 'px';
          gsap.to(items, { opacity: 0, y: -6, duration: 0.2, ease: 'power2.in' });
          gsap.to(submenu, {
            height: 0,
            opacity: 0,
            duration: 0.3,
            ease: 'power2.in',
            delay: 0.08,
            onComplete: function () {
              submenu.hidden = true;
              submenu.setAttribute('aria-hidden', 'true');
              submenu.style.height = '';
              submenu.style.overflow = '';
              btn.setAttribute('aria-expanded', 'false');
              accordion.classList.remove('is-open');
              gsap.set([submenu, items], { clearProps: 'all' });
            }
          });
        } else {
          submenu.hidden = true;
          submenu.setAttribute('aria-hidden', 'true');
          btn.setAttribute('aria-expanded', 'false');
          accordion.classList.remove('is-open');
        }
      }

      triggers.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var submenuId = btn.getAttribute('data-submenu-id');
          var submenu = submenuId && document.getElementById(submenuId);
          var accordion = btn.closest('.mobile-menu__accordion');
          var expanded = btn.getAttribute('aria-expanded') === 'true';
          if (submenu && accordion) {
            if (expanded) {
              closeAccordion(btn, submenu, accordion);
            } else {
              openAccordion(btn, submenu, accordion);
            }
          }
        });
      });
    })();
  }
})();
