/**
 * Systemiks: header scroll, line-reveal, rotating words, offcanvas menu, accordion, smooth scroll
 */
(function () {
  'use strict';

  var body = document.body;
  var header = document.getElementById('header-outer');

  // ----- Header: entrance + scroll background -----
  if (header) {
    header.classList.add('entrance-animation');
    function onScroll() {
      header.classList.toggle('scrolled', window.scrollY > 50);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ----- Offcanvas menu: open/close + snapshot current viewport -----
  var menuOpen = document.querySelector('.js-menu-open');
  var menuClose = document.querySelector('.offcanvas-close');
  var backdrop = document.getElementById('offcanvas-backdrop');
  var panel = document.getElementById('offcanvas-menu');
  var siteWrap = document.getElementById('site-wrap');
  var siteWrapInner = siteWrap ? siteWrap.querySelector('.site-wrap-inner') : null;

  function applyMenuSnapshot() {
    if (!body.classList.contains('offcanvas-open')) return;
    var wrap = document.getElementById('site-wrap');
    var inner = wrap ? wrap.querySelector('.site-wrap-inner') : null;
    if (!wrap || !inner) return;

    var boxW = wrap.clientWidth;
    var boxH = wrap.clientHeight;
    var innerW = inner.offsetWidth || 1;
    var innerH = inner.offsetHeight || 1;
    if (boxW <= 0 || boxH <= 0 || innerW <= 0 || innerH <= 0) return;

    var s = Math.min(boxW / innerW, boxH / innerH, 1);
    var scaledW = innerW * s;
    var scaledH = innerH * s;
    var tx = (boxW - scaledW) / 2;
    var ty = (boxH - scaledH) / 2;
    inner.style.transform = 'translate(' + tx + 'px, ' + ty + 'px) scale(' + s + ')';
  }

  function openMenu() {
    body.classList.add('offcanvas-open');
    if (panel) panel.setAttribute('aria-hidden', 'false');
    if (menuOpen) menuOpen.setAttribute('aria-expanded', 'true');
    requestAnimationFrame(function () {
      requestAnimationFrame(applyMenuSnapshot);
    });
  }
  function closeMenu() {
    body.classList.remove('offcanvas-open');
    if (panel) panel.setAttribute('aria-hidden', 'true');
    if (menuOpen) menuOpen.setAttribute('aria-expanded', 'false');
    var inner = siteWrap ? siteWrap.querySelector('.site-wrap-inner') : null;
    if (inner) inner.style.transform = '';
  }

  if (menuOpen) menuOpen.addEventListener('click', openMenu);
  if (menuClose) menuClose.addEventListener('click', closeMenu);
  if (backdrop) backdrop.addEventListener('click', closeMenu);

  window.addEventListener('resize', function () {
    if (body.classList.contains('offcanvas-open')) applyMenuSnapshot();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && body.classList.contains('offcanvas-open')) closeMenu();
  });

  // ----- Line-reveal headings -----
  var splitHeadings = document.querySelectorAll('.split-heading');
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { rootMargin: '0px 0px -15% 0px', threshold: 0 });
  splitHeadings.forEach(function (el) { observer.observe(el); });

  // ----- Scroll reveal: sections and work comparison -----
  var revealObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) entry.target.classList.add('is-visible');
    });
  }, { rootMargin: '0px 0px -12% 0px', threshold: 0 });
  document.querySelectorAll('.reveal-on-scroll').forEach(function (el) { revealObserver.observe(el); });

  var workComparison = document.querySelector('.work-comparison');
  if (workComparison) {
    var workObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) entry.target.classList.add('revealed');
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0 });
    workObserver.observe(workComparison);
  }

  // ----- Rotating words -----
  var rotatingWrap = document.querySelector('.rotating-words');
  if (rotatingWrap) {
    var words = (rotatingWrap.dataset.words || 'Web|SEO|Growth|Brands').split('|');
    var span = rotatingWrap.querySelector('.dynamic-word span');
    if (span) {
      var i = 0;
      span.textContent = words[0];
      setInterval(function () {
        i = (i + 1) % words.length;
        span.style.animation = 'none';
        span.offsetHeight;
        span.style.animation = '';
        span.textContent = words[i];
      }, 3000);
    }
  }

  // ----- Accordion -----
  document.querySelectorAll('.accordion').forEach(function (list) {
    var items = list.querySelectorAll('.accordion-item');
    items.forEach(function (item) {
      var trigger = item.querySelector('.accordion-trigger');
      var icon = item.querySelector('.icon');
      if (!trigger) return;
      trigger.addEventListener('click', function () {
        var wasOpen = item.classList.contains('is-open');
        items.forEach(function (x) {
          x.classList.remove('is-open');
          var i = x.querySelector('.icon');
          if (i) i.textContent = '+';
        });
        if (!wasOpen) {
          item.classList.add('is-open');
          if (icon) icon.textContent = '−';
        }
      });
    });
  });

  // ----- Services section: tab click → copy + bg shapes (one simple exit/enter, no glitch) -----
  var servicesTablist = document.getElementById('services-tablist');
  var servicesSectionBg = document.querySelector('.services-section-bg');
  var servicesBgT1 = null;
  var servicesBgT2 = null;
  if (servicesTablist) {
    var tabs = servicesTablist.querySelectorAll('a[data-service]');
    var copies = document.querySelectorAll('.services-content .copy[data-service]');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var service = tab.getAttribute('data-service');
        if (!service) return;
        var wasActive = tab.classList.contains('active');
        tabs.forEach(function (t) {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        copies.forEach(function (p) {
          p.classList.toggle('copy--active', p.getAttribute('data-service') === service);
        });
        if (servicesSectionBg && !wasActive) {
          if (servicesBgT1) clearTimeout(servicesBgT1);
          if (servicesBgT2) clearTimeout(servicesBgT2);
          servicesSectionBg.classList.remove('services-bg-entering');
          servicesSectionBg.classList.add('services-bg-exiting');
          servicesBgT1 = setTimeout(function () {
            servicesBgT1 = null;
            servicesSectionBg.classList.remove('services-bg-exiting');
            servicesSectionBg.classList.remove('services-bg-tab-website', 'services-bg-tab-branding', 'services-bg-tab-seo', 'services-bg-tab-advertising');
            servicesSectionBg.classList.add('services-bg-tab-' + service);
            servicesSectionBg.classList.add('services-bg-entering');
            servicesBgT2 = setTimeout(function () {
              servicesBgT2 = null;
              servicesSectionBg.classList.remove('services-bg-entering');
            }, 350);
          }, 350);
        }
      });
    });
  }

  // ----- Smooth scroll for # anchors -----
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    var id = a.getAttribute('href');
    if (id === '#') return;
    a.addEventListener('click', function (e) {
      if (a.closest('.services-list')) return;
      var target = document.querySelector(id);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
})();
