(function () {
  "use strict";

  // Only run on Our Story page
  if (!window.location.pathname.includes("our-story")) {
    return;
  }

  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
    console.warn("Our Story Animations: GSAP or ScrollTrigger not loaded.");
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  // =========================================================
  // UTILITIES
  // =========================================================

  function elementExists(selector) {
    return document.querySelector(selector) !== null;
  }

  function isMobile() {
    return window.innerWidth < 768;
  }

  function cleanupAllScrollTriggers() {
    ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
  }

  function prefersReducedMotion() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  window.addEventListener("beforeunload", cleanupAllScrollTriggers);

  // =========================================================
  // GENERIC SECTION REVEALS (.fade-up + .text-reveal-inner)
  // =========================================================

  function revealSection(sectionSelector) {
    const section = document.querySelector(sectionSelector);
    if (!section) return;

    const textReveals = section.querySelectorAll(".text-reveal-inner");
    const fadeUps = section.querySelectorAll(".fade-up");

    if (prefersReducedMotion()) {
      if (textReveals.length) gsap.set(textReveals, { y: "0%", clearProps: "transform" });
      if (fadeUps.length) gsap.set(fadeUps, { opacity: 1, y: 0, clearProps: "transform" });
      return;
    }

    if (textReveals.length) {
      gsap.set(textReveals, { y: "110%" });
      gsap.to(textReveals, {
        y: "0%",
        duration: 0.9,
        stagger: 0.12,
        ease: "power3.out",
        scrollTrigger: {
          trigger: section,
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }

    if (fadeUps.length) {
      gsap.set(fadeUps, { opacity: 0, y: isMobile() ? 24 : 40 });
      gsap.to(fadeUps, {
        opacity: 1,
        y: 0,
        duration: 0.7,
        stagger: 0.1,
        ease: "power2.out",
        scrollTrigger: {
          trigger: section,
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }
  }

  // =========================================================
  // HERO ENTRANCE (Our Story layout — image + fade-up, not video)
  // =========================================================

  function initHeroAnimations() {
    const hero = document.querySelector("#hero");
    if (!hero) return;

    const textReveals = hero.querySelectorAll(".text-reveal-inner");
    const fadeUps = hero.querySelectorAll(".fade-up");

    if (prefersReducedMotion()) {
      if (textReveals.length) gsap.set(textReveals, { y: "0%", clearProps: "transform" });
      if (fadeUps.length) gsap.set(fadeUps, { opacity: 1, y: 0, clearProps: "transform" });
      return;
    }

    if (textReveals.length) gsap.set(textReveals, { y: "110%" });
    if (fadeUps.length) gsap.set(fadeUps, { opacity: 0, y: 30 });

    const heroTl = gsap.timeline({ delay: 0.3 });

    if (textReveals.length) {
      heroTl.to(
        textReveals,
        {
          y: "0%",
          duration: 0.9,
          stagger: 0.12,
          ease: "power3.out",
        },
        0.2,
      );
    }

    if (fadeUps.length) {
      heroTl.to(
        fadeUps,
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          stagger: 0.1,
          ease: "power2.out",
        },
        0.5,
      );
    }

    // Subtle parallax on hero content while scrolling
    if (elementExists(".hero__content")) {
      gsap.to(".hero__content", {
        y: isMobile() ? -30 : -60,
        ease: "none",
        scrollTrigger: {
          trigger: "#hero",
          start: "top top",
          end: "bottom top",
          scrub: 1.5,
        },
      });
    }

    return heroTl;
  }

  // =========================================================
  // IMPACT STATS (adapted from homepage #numbers)
  // =========================================================

  function animateCounter(element, target, duration) {
    const obj = { value: 0 };

    gsap.to(obj, {
      value: target,
      duration: duration,
      ease: "power2.out",
      onUpdate: function () {
        element.textContent = Math.round(obj.value).toLocaleString("en-US");
      },
      onComplete: function () {
        element.textContent = target.toLocaleString("en-US");
      },
    });
  }

  function getCounterDuration(target) {
    if (target >= 1000) return 2.0;
    if (target >= 100) return 1.8;
    if (target >= 50) return 1.5;
    if (target >= 20) return 1.2;
    return 1.0;
  }

  function initNumbersAnimations() {
    // Our Story uses #impact; keep #numbers for compatibility
    const section =
      document.querySelector("#impact") || document.querySelector("#numbers");
    if (!section) return;

    const sectionId = section.id;

    if (prefersReducedMotion()) {
      gsap.set(section.querySelectorAll(".text-reveal-inner, .fade-up"), {
        clearProps: "all",
        opacity: 1,
        y: 0,
      });
      gsap.set(section.querySelectorAll(".text-reveal-inner"), { y: "0%" });

      section.querySelectorAll("[data-counter-target]").forEach((card) => {
        const target = parseInt(card.getAttribute("data-counter-target"), 10);
        const counterEl = card.querySelector("[data-counter]");
        if (!isNaN(target) && counterEl) {
          counterEl.textContent = target.toLocaleString("en-US");
        }
      });
      return;
    }

    revealSection("#" + sectionId);

    // Optional counters (if markup adds data-counter-target later)
    section.querySelectorAll("[data-counter-target]").forEach((card) => {
      const target = parseInt(card.getAttribute("data-counter-target"), 10);
      const counterEl = card.querySelector("[data-counter]");
      if (isNaN(target) || !counterEl) return;

      ScrollTrigger.create({
        trigger: card,
        start: "top 85%",
        once: true,
        onEnter: () =>
          animateCounter(counterEl, target, getCounterDuration(target)),
      });
    });
  }

  // =========================================================
  // CEO MESSAGE (shared section)
  // =========================================================

  function initCEOAnimations() {
    if (!elementExists("#ceo-message")) return;

    const headingLines = document.querySelectorAll(
      "#ceo-message .text-reveal-inner",
    );

    if (headingLines.length) {
      gsap.fromTo(
        headingLines,
        { y: "110%" },
        {
          y: "0%",
          duration: 0.9,
          stagger: 0.12,
          ease: "power3.out",
          scrollTrigger: {
            trigger: "#ceo-message",
            start: "top 70%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const ceoImage = document.querySelector(".ceo__image");
    if (ceoImage) {
      gsap.fromTo(
        ceoImage,
        { opacity: 0 },
        {
          opacity: 1,
          duration: 0.6,
          ease: "power2.out",
          scrollTrigger: {
            trigger: ".ceo__image-col",
            start: "top 70%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: "#ceo-message",
        start: "top 75%",
        toggleActions: "play none none none",
      },
    });

    tl.fromTo(
      ".ceo__image-wrapper",
      { opacity: 0, scale: 0.95 },
      { opacity: 1, scale: 1, duration: 0.9, ease: "power2.out" },
    );

    tl.fromTo(
      [".ceo__quote", ".ceo__body", ".ceo__signature", "#ceo-message .section-label"],
      { opacity: 0, y: 40 },
      {
        opacity: 1,
        y: 0,
        stagger: 0.15,
        duration: 0.8,
        ease: "power2.out",
      },
      "-=0.4",
    );
  }

  // =========================================================
  // ACCREDITATIONS SLIDER (shared section)
  // =========================================================

  function initInfiniteSlider(trackSelector, wrapperSelector, options = {}) {
    const {
      duration = 50,
      direction = "left",
      enableOnMobile = true,
    } = options;

    if (!enableOnMobile && isMobile()) return null;

    const sliderTrack = document.querySelector(trackSelector);
    const sliderWrapper = document.querySelector(wrapperSelector);
    if (!sliderTrack || !sliderWrapper) return null;

    const cards = sliderTrack.children;
    if (!cards.length) return null;

    Array.from(cards).forEach((card) => {
      sliderTrack.appendChild(card.cloneNode(true));
    });

    sliderTrack.style.willChange = "transform";
    const totalWidth = sliderTrack.scrollWidth / 2;
    const targetX = direction === "left" ? -totalWidth : totalWidth;

    const sliderTl = gsap.timeline({
      repeat: -1,
      defaults: { ease: "none" },
      onRepeat: () => gsap.set(sliderTrack, { x: 0 }),
    });

    sliderTl.to(sliderTrack, { x: targetX, duration: duration, ease: "none" });

    sliderWrapper.addEventListener("mouseenter", () => sliderTl.pause(), {
      passive: true,
    });
    sliderWrapper.addEventListener("mouseleave", () => sliderTl.play(), {
      passive: true,
    });

    return sliderTl;
  }

  function initAccreditationsAnimations() {
    if (!elementExists("#accreditations")) return;

    const fades = [
      { selector: "#accreditations .section-label", y: 20, duration: 0.6 },
      { selector: "#accreditations .text-reveal-inner", y: 0, duration: 0.9 },
      { selector: ".accreditations__subheading", y: 30, duration: 0.8, delay: 0.1 },
      { selector: ".accreditations__badges", y: 20, duration: 0.6, stagger: 0.1 },
      { selector: ".accreditations__trust", y: 20, duration: 0.6, delay: 0.3 },
    ];

    if (prefersReducedMotion()) {
      gsap.set(
        [
          "#accreditations .section-label",
          "#accreditations .text-reveal-inner",
          ".accreditations__subheading",
          ".accreditations__badges",
          ".accreditations__trust",
          ".accred-card",
        ],
        { opacity: 1, y: 0, x: 0, scale: 1 },
      );
      return;
    }

    initInfiniteSlider(".accred-slider-track", ".accred-slider-wrapper", {
      duration: 50,
      direction: "left",
      enableOnMobile: true,
    });

    const textReveals = document.querySelectorAll(
      "#accreditations .text-reveal-inner",
    );
    if (textReveals.length) {
      gsap.set(textReveals, { y: "110%" });
      gsap.to(textReveals, {
        y: "0%",
        duration: 0.9,
        stagger: 0.12,
        ease: "power3.out",
        scrollTrigger: {
          trigger: "#accreditations",
          start: "top 80%",
          toggleActions: "play none none none",
          once: true,
        },
      });
    }

    fades.forEach((f) => {
      if (f.selector.includes("text-reveal-inner")) return;
      const els = document.querySelectorAll(f.selector);
      if (!els.length) return;

      gsap.fromTo(
        els,
        { opacity: 0, y: f.y },
        {
          opacity: 1,
          y: 0,
          duration: f.duration,
          delay: f.delay || 0,
          stagger: f.stagger || 0,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#accreditations",
            start: "top 80%",
            toggleActions: "play none none none",
            once: true,
          },
        },
      );
    });

    const cards = document.querySelectorAll(
      ".accred-slider-track .accred-card",
    );
    if (cards.length && !isMobile()) {
      gsap.fromTo(
        cards,
        { scale: 0.9, opacity: 0 },
        {
          scale: 1,
          opacity: 1,
          duration: 0.7,
          stagger: 0.05,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#accreditations",
            start: "top 80%",
            toggleActions: "play none none none",
            once: true,
          },
        },
      );
    }
  }

  // =========================================================
  // FINAL CTA
  // =========================================================

  function initFinalCTAAnimations() {
    if (!elementExists("#final-cta")) return;

    if (prefersReducedMotion()) {
      gsap.set(["#final-cta .text-reveal-inner", "#final-cta .fade-up"], {
        clearProps: "all",
        opacity: 1,
      });
      gsap.set("#final-cta .text-reveal-inner", { y: "0%" });
      return;
    }

    const sectionLabel = document.querySelector("#final-cta .section-label");
    if (sectionLabel) {
      gsap.fromTo(
        sectionLabel,
        { opacity: 0, y: 16 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#final-cta",
            start: "top 70%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const headingInner = document.querySelector("#final-cta .text-reveal-inner");
    if (headingInner) {
      gsap.fromTo(
        headingInner,
        { y: "110%" },
        {
          y: "0%",
          duration: 0.9,
          ease: "power3.out",
          scrollTrigger: {
            trigger: "#final-cta",
            start: "top 70%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const subtitle = document.querySelector("#final-cta .final-cta__subtitle");
    if (subtitle) {
      gsap.fromTo(
        subtitle,
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#final-cta",
            start: "top 70%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const buttons = document.querySelectorAll("#final-cta .final-cta__btn");
    if (buttons.length) {
      gsap.fromTo(
        buttons,
        { opacity: 0, y: 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          stagger: 0.1,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#final-cta .final-cta__buttons",
            start: "top 70%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const phone = document.querySelector("#final-cta .final-cta__phone");
    if (phone) {
      gsap.fromTo(
        phone,
        { opacity: 0, y: 15 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#final-cta .final-cta__phone",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }
  }

  // =========================================================
  // FOOTER
  // =========================================================

  function initFooterAnimations() {
    if (!elementExists("#footer")) return;

    const yearEl = document.querySelector("[data-current-year]");
    if (yearEl) {
      yearEl.textContent = new Date().getFullYear();
    }

    const newsletterForm = document.querySelector("[data-newsletter-form]");
    if (newsletterForm) {
      newsletterForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const input = newsletterForm.querySelector(".footer__newsletter-input");
        const btn = newsletterForm.querySelector(
          ".footer__newsletter-btn span",
        );
        if (input && input.value && btn) {
          const originalText = btn.textContent;
          btn.textContent = "Subscribed ✓";
          input.value = "";
          setTimeout(() => {
            btn.textContent = originalText;
          }, 2500);
        }
      });
    }

    if (prefersReducedMotion()) return;

    const cols = document.querySelectorAll(".footer__col");
    const bottom = document.querySelector(".footer__bottom");

    if (cols.length) gsap.set(cols, { opacity: 0, y: 30 });
    if (bottom) gsap.set(bottom, { opacity: 0, y: 20 });

    if (cols.length) {
      gsap.to(cols, {
        opacity: 1,
        y: 0,
        duration: 0.7,
        stagger: 0.12,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#footer",
          start: "top 85%",
          toggleActions: "play none none none",
        },
      });
    }

    if (bottom) {
      gsap.to(bottom, {
        opacity: 1,
        y: 0,
        duration: 0.6,
        delay: 0.4,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#footer",
          start: "top 85%",
          toggleActions: "play none none none",
        },
      });
    }
  }

  // =========================================================
  // INITIALIZE
  // =========================================================

  function initOurStoryAnimations() {
    if (window.__ourStoryAnimationsStarted) return;
    window.__ourStoryAnimationsStarted = true;

    // Mark so homepage animations.js does not double-init
    window.__animationsStarted = true;

    initHeroAnimations();

    // Content sections with .fade-up / .text-reveal-inner
    revealSection("#beginning");
    revealSection("#today");
    initNumbersAnimations(); // #impact
    revealSection("#journey");
    revealSection("#vision");
    revealSection("#awards");

    initCEOAnimations();
    initAccreditationsAnimations();
    revealSection("#faculty-insights");
    revealSection("#video-testimonials");
    initFinalCTAAnimations();
    initFooterAnimations();

    if (typeof ScrollTrigger !== "undefined") {
      ScrollTrigger.refresh();
    }
  }

  function startAnimations() {
    initOurStoryAnimations();
  }

  if (window.__lenisReady) {
    startAnimations();
  } else {
    document.addEventListener("lenisReady", startAnimations, { once: true });
  }

  setTimeout(function () {
    if (!window.__ourStoryAnimationsStarted) {
      console.warn(
        "Our Story: lenisReady never fired – starting animations fallback",
      );
      startAnimations();
    }
  }, 800);
  // ========================================
  // Journey Timeline Scroll Reveal
  // ========================================
  document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.journey__item');
    
    if (!timelineItems.length) return;

    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    timelineItems.forEach(item => observer.observe(item));
  });
})();
