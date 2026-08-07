/**
 * Blog & News — Shared Detail Page JS
 * Maverick Business Academy
 *
 * Features:
 *  - Reading progress bar (blog + news detail)
 *  - TOC active state tracking via IntersectionObserver
 *  - GSAP entrance animations for detail pages
 */

(function () {
  'use strict';

  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    console.warn('Blog JS: GSAP or ScrollTrigger not loaded.');
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // =========================================================
  // 1. READING PROGRESS BAR
  // =========================================================

  function initProgressBar() {
    const progressBar = document.getElementById('blog-progress-bar') || document.getElementById('news-progress-bar');
    const progressFill = document.getElementById('blog-progress-fill') || document.getElementById('news-progress-fill');

    if (!progressBar || !progressFill) return;

    let ticking = false;

    function updateProgress() {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const progress = docHeight > 0 ? Math.min((scrollTop / docHeight) * 100, 100) : 0;
      progressFill.style.width = progress + '%';
      ticking = false;
    }

    function onScroll() {
      if (!ticking) {
        window.requestAnimationFrame(updateProgress);
        ticking = true;
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    updateProgress();
  }

  // =========================================================
  // 2. TOC ACTIVE TRACKING (Blog Detail only)
  // =========================================================

  function initTocTracking() {
    const tocLinks = document.querySelectorAll('.blog-toc__link');
    if (!tocLinks.length) return;

    const headingIds = Array.from(tocLinks)
      .map(function (link) { return link.getAttribute('href'); })
      .map(function (href) { return href ? href.substring(1) : null; })
      .filter(Boolean);

    if (!headingIds.length) return;

    // Scroll spy: highlight current section in TOC
    var observerOptions = {
      root: null,
      rootMargin: '-20% 0px -60% 0px',
      threshold: 0,
    };

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var id = entry.target.id;
          tocLinks.forEach(function (link) {
            link.classList.toggle('blog-toc__link--active', link.getAttribute('href') === '#' + id);
          });
        }
      });
    }, observerOptions);

    headingIds.forEach(function (id) {
      var el = document.getElementById(id);
      if (el) observer.observe(el);
    });
  }

  // =========================================================
  // 3. TOC COLLAPSE TOGGLE (Blog Detail only)
  // =========================================================

  function initTocToggle() {
    var toggleBtn = document.querySelector('.blog-toc__toggle');
    var tocList = document.querySelector('.blog-toc__list');

    if (!toggleBtn || !tocList) return;

    toggleBtn.addEventListener('click', function () {
      var isOpen = !tocList.classList.contains('collapsed');
      tocList.classList.toggle('collapsed', isOpen);
      toggleBtn.classList.toggle('open', !isOpen);
      toggleBtn.querySelector('.blog-toc__toggle-text').textContent = isOpen ? 'Show' : 'Hide';
      toggleBtn.setAttribute('aria-expanded', String(!isOpen));
    });
  }

  // =========================================================
  // 4. GSAP ENTRANCE ANIMATIONS — Blog Detail
  // =========================================================

  function initBlogDetailAnimations() {
    if (prefersReducedMotion) {
      gsap.set('.blog-detail-hero__eyebrow, .blog-detail-hero__title, .blog-detail-hero__excerpt, .blog-detail-hero__scroll-hint', { clearProps: 'all', opacity: 1 });
      gsap.set('.blog-article-header__badge, .blog-article-header__title, .blog-article-header__excerpt, .blog-article-header__byline', { clearProps: 'all', opacity: 1 });
      gsap.set('.blog-featured-image-box img', { clearProps: 'all', opacity: 1, scale: 1 });
      gsap.set('.blog-toc, .blog-share-bar', { clearProps: 'all', opacity: 1 });
      gsap.set('.blog-card, .blog-related__title', { clearProps: 'all', opacity: 1 });
      return;
    }

    // Hero content
    gsap.fromTo('.blog-detail-hero__eyebrow', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out', scrollTrigger: { trigger: '.blog-detail-hero', start: 'top 80%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-detail-hero__title', { opacity: 0, y: 24 }, { opacity: 1, y: 0, duration: 0.7, delay: 0.1, ease: 'power2.out', scrollTrigger: { trigger: '.blog-detail-hero', start: 'top 75%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-detail-hero__excerpt', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, delay: 0.2, ease: 'power2.out', scrollTrigger: { trigger: '.blog-detail-hero', start: 'top 70%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-detail-hero__scroll-hint', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.5, delay: 0.35, ease: 'power2.out', scrollTrigger: { trigger: '.blog-detail-hero', start: 'top 60%', toggleActions: 'play none none none' } });

    // Article header
    gsap.fromTo('.blog-article-header__badge', { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out', scrollTrigger: { trigger: '.blog-article-header', start: 'top 85%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-article-header__title', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.65, delay: 0.08, ease: 'power2.out', scrollTrigger: { trigger: '.blog-article-header', start: 'top 82%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-article-header__excerpt', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.55, delay: 0.16, ease: 'power2.out', scrollTrigger: { trigger: '.blog-article-header', start: 'top 78%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-article-header__byline', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.5, delay: 0.24, ease: 'power2.out', scrollTrigger: { trigger: '.blog-article-header', start: 'top 74%', toggleActions: 'play none none none' } });

    // Featured image
    gsap.fromTo('.blog-featured-image-box img', { opacity: 0, scale: 0.95 }, { opacity: 1, scale: 1, duration: 0.7, ease: 'power2.out', scrollTrigger: { trigger: '.blog-featured-image-box', start: 'top 80%', toggleActions: 'play none none none' } });

    // Sidebar elements (TOC + share bar)
    gsap.fromTo('.blog-toc', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out', scrollTrigger: { trigger: '.blog-detail-sidebar', start: 'top 75%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-share-bar', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.5, delay: 0.1, ease: 'power2.out', scrollTrigger: { trigger: '.blog-share-bar', start: 'top 75%', toggleActions: 'play none none none' } });

    // Related posts
    gsap.fromTo('.blog-related__title', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out', scrollTrigger: { trigger: '.blog-related', start: 'top 85%', toggleActions: 'play none none none' } });
    gsap.fromTo('.blog-related__grid .blog-card', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power2.out', scrollTrigger: { trigger: '.blog-related__grid', start: 'top 80%', toggleActions: 'play none none none' } });

    // Article body headings — stagger per heading
    var headings = document.querySelectorAll('.blog-article-body h2, .blog-article-body h3');
    headings.forEach(function (heading, i) {
      gsap.fromTo(heading, { opacity: 0, y: 16 }, {
        opacity: 1, y: 0,
        duration: 0.5,
        delay: i * 0.05,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: heading,
          start: 'top 88%',
          toggleActions: 'play none none none',
        },
      });
    });
  }

  // =========================================================
  // 5. GSAP ENTRANCE ANIMATIONS — News Detail
  // =========================================================

  function initNewsDetailAnimations() {
    if (prefersReducedMotion) {
      gsap.set('.news-detail-hero__eyebrow, .news-detail-hero__title, .news-detail-hero__scroll-hint', { clearProps: 'all', opacity: 1 });
      gsap.set('.news-editorial-header__badge-row, .news-editorial-header__title, .news-editorial-header__excerpt, .news-editorial-header__byline', { clearProps: 'all', opacity: 1 });
      gsap.set('.news-detail-image-box img', { clearProps: 'all', opacity: 1 });
      gsap.set('.news-detail-sidebar .blog-share-bar', { clearProps: 'all', opacity: 1 });
      gsap.set('.news-more-updates__title, .news-feed .news-row', { clearProps: 'all', opacity: 1 });
      return;
    }

    // Hero
    gsap.fromTo('.news-detail-hero__eyebrow', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out', scrollTrigger: { trigger: '.news-detail-hero', start: 'top 80%', toggleActions: 'play none none none' } });
    gsap.fromTo('.news-detail-hero__title', { opacity: 0, y: 22 }, { opacity: 1, y: 0, duration: 0.65, delay: 0.1, ease: 'power2.out', scrollTrigger: { trigger: '.news-detail-hero', start: 'top 75%', toggleActions: 'play none none none' } });
    gsap.fromTo('.news-detail-hero__scroll-hint', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.5, delay: 0.25, ease: 'power2.out', scrollTrigger: { trigger: '.news-detail-hero', start: 'top 65%', toggleActions: 'play none none none' } });

    // Editorial header
    gsap.fromTo('.news-editorial-header__badge-row', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out', scrollTrigger: { trigger: '.news-editorial-header', start: 'top 85%', toggleActions: 'play none none none' } });
    gsap.fromTo('.news-editorial-header__title', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, delay: 0.08, ease: 'power2.out', scrollTrigger: { trigger: '.news-editorial-header', start: 'top 82%', toggleActions: 'play none none none' } });
    gsap.fromTo('.news-editorial-header__excerpt', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.55, delay: 0.16, ease: 'power2.out', scrollTrigger: { trigger: '.news-editorial-header', start: 'top 78%', toggleActions: 'play none none none' } });
    gsap.fromTo('.news-editorial-header__byline', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.5, delay: 0.24, ease: 'power2.out', scrollTrigger: { trigger: '.news-editorial-header', start: 'top 74%', toggleActions: 'play none none none' } });

    // Featured image
    gsap.fromTo('.news-detail-image-box img', { opacity: 0, scale: 0.95 }, { opacity: 1, scale: 1, duration: 0.65, ease: 'power2.out', scrollTrigger: { trigger: '.news-detail-image-box', start: 'top 75%', toggleActions: 'play none none none' } });

    // Share bar (sidebar)
    gsap.fromTo('.news-detail-sidebar .blog-share-bar', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out', scrollTrigger: { trigger: '.news-detail-sidebar', start: 'top 75%', toggleActions: 'play none none none' } });

    // More updates
    gsap.fromTo('.news-more-updates__title', { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out', scrollTrigger: { trigger: '.news-more-updates', start: 'top 85%', toggleActions: 'play none none none' } });
    gsap.fromTo('.news-feed .news-row', { opacity: 0, x: -16 }, { opacity: 1, x: 0, duration: 0.45, stagger: 0.07, ease: 'power2.out', scrollTrigger: { trigger: '.news-feed', start: 'top 80%', toggleActions: 'play none none none' } });

    // Article body headings
    var headings = document.querySelectorAll('.news-article-body h2, .news-article-body h3');
    headings.forEach(function (heading, i) {
      gsap.fromTo(heading, { opacity: 0, y: 14 }, {
        opacity: 1, y: 0,
        duration: 0.45,
        delay: i * 0.04,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: heading,
          start: 'top 88%',
          toggleActions: 'play none none none',
        },
      });
    });
  }

  // =========================================================
  // INIT
  // =========================================================

  document.addEventListener('DOMContentLoaded', function () {
    initProgressBar();

    if (document.querySelector('.blog-detail')) {
      initTocTracking();
      initTocToggle();
      initBlogDetailAnimations();
    }

    if (document.querySelector('.news-detail')) {
      initNewsDetailAnimations();
    }
  });
})();
