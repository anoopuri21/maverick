/* =========================================================
   ANIMATION UTILITIES — Shared Reusable Functions
   Maverick Business Academy
   ========================================================= */

const AnimationUtils = (() => {
  "use strict";

  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;

  // =========================================================
  // UTILITY: Check if element exists
  // =========================================================
  function exists(selector) {
    return document.querySelector(selector) !== null;
  }

  // =========================================================
  // UTILITY: Get responsive config based on screen width
  // =========================================================
  function responsive(desktop, mobile) {
    return window.innerWidth <= 768 ? mobile : desktop;
  }

  // =========================================================
  // UTILITY: Set reduced motion state (make everything visible)
  // =========================================================
  function setReducedMotion(selectors) {
    if (!selectors || !selectors.length) return;
    gsap.set(selectors, { clearProps: "all", opacity: 1 });
    // Reset text-reveal elements to visible
    const textReveals = selectors.filter(
      (s) => s.includes("text-reveal") || s.includes("fade-up"),
    );
    if (textReveals.length) {
      gsap.set(textReveals, { y: "0%" });
    }
  }

  // =========================================================
  // ANIMATION: Text Reveal (y: 110% → 0%)
  // Used by: All section headings with .text-reveal-inner
  // =========================================================
  function textReveal(selector, options = {}) {
    const {
      trigger = null,
      start = "top 75%",
      stagger = 0.12,
      duration = 0.9,
      ease = "power3.out",
      delay = 0,
    } = options;

    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    gsap.fromTo(
      elements,
      { y: "110%" },
      {
        y: "0%",
        duration,
        stagger,
        delay,
        ease,
        scrollTrigger: {
          trigger: trigger || elements[0].closest("section"),
          start,
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Fade Up (opacity: 0, y → 1, 0)
  // Used by: Body text, cards, CTAs, subtitles
  // =========================================================
  function fadeUp(selector, options = {}) {
    const {
      trigger = null,
      start = "top 80%",
      y = 30,
      duration = 0.8,
      stagger = 0,
      delay = 0,
      ease = "power2.out",
    } = options;

    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    gsap.fromTo(
      elements,
      { opacity: 0, y },
      {
        opacity: 1,
        y: 0,
        duration,
        stagger,
        delay,
        ease,
        scrollTrigger: {
          trigger: trigger || elements[0].closest("section"),
          start,
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Section Label (opacity: 0, y: 16 → 1, 0)
  // Used by: All .section-label elements
  // =========================================================
  function sectionLabel(sectionSelector) {
    const label = document.querySelector(`${sectionSelector} .section-label`);
    if (!label) return;

    gsap.fromTo(
      label,
      { opacity: 0, y: 16 },
      {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: "power2.out",
        scrollTrigger: {
          trigger: sectionSelector,
          start: "top 75%",
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Cards Grid (staggered fade-up)
  // Used by: Card grids in multiple sections
  // =========================================================
  function cards(selector, options = {}) {
    const {
      trigger = null,
      start = "top 75%",
      y = 40,
      duration = 0.7,
      stagger = 0.1,
      ease = "power2.out",
    } = options;

    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    gsap.fromTo(
      elements,
      { opacity: 0, y },
      {
        opacity: 1,
        y: 0,
        duration,
        stagger,
        ease,
        scrollTrigger: {
          trigger: trigger || elements[0].closest("section"),
          start,
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Scale In (scale: 0.9 → 1)
  // Used by: Images, badges
  // =========================================================
  function scaleIn(selector, options = {}) {
    const {
      trigger = null,
      start = "top 80%",
      scale = 0.9,
      duration = 0.7,
      stagger = 0,
      delay = 0,
    } = options;

    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    gsap.fromTo(
      elements,
      { opacity: 0, scale },
      {
        opacity: 1,
        scale: 1,
        duration,
        stagger,
        delay,
        ease: "power2.out",
        scrollTrigger: {
          trigger: trigger || elements[0].closest("section"),
          start,
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Horizontal Slide (x → 0)
  // Used by: Side-scrolling cards, lists
  // =========================================================
  function slideIn(selector, options = {}) {
    const {
      trigger = null,
      start = "top 75%",
      x = 40,
      duration = 0.6,
      stagger = 0.1,
      delay = 0,
    } = options;

    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    gsap.fromTo(
      elements,
      { opacity: 0, x },
      {
        opacity: 1,
        x: 0,
        duration,
        stagger,
        delay,
        ease: "power2.out",
        scrollTrigger: {
          trigger: trigger || elements[0].closest("section"),
          start,
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Line Scale (scaleX: 0 → 1)
  // Used by: Dividers, connector lines
  // =========================================================
  function lineScale(selector, options = {}) {
    const {
      trigger = null,
      start = "top 85%",
      duration = 0.8,
      stagger = 0,
      delay = 0,
      origin = "left center",
    } = options;

    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    gsap.fromTo(
      elements,
      { scaleX: 0, transformOrigin: origin },
      {
        scaleX: 1,
        duration,
        stagger,
        delay,
        ease: "power2.inOut",
        scrollTrigger: {
          trigger: trigger || elements[0].closest("section"),
          start,
          toggleActions: "play none none none",
        },
      },
    );
  }

  // =========================================================
  // ANIMATION: Counter (number animation)
  // Used by: Stat counters
  // =========================================================
  function counter(element, target, options = {}) {
    const { duration = getCounterDuration(target), ease = "power2.out" } =
      options;

    const obj = { value: 0 };
    gsap.to(obj, {
      value: target,
      duration,
      ease,
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

  // =========================================================
  // ANIMATION: Parallax (scrub-based movement)
  // Used by: Images, backgrounds
  // =========================================================
  function parallax(selector, options = {}) {
    const {
      trigger = null,
      start = "top bottom",
      end = "bottom top",
      y = -40,
      scrub = 1.5,
    } = options;

    const element = document.querySelector(selector);
    if (!element) return;

    gsap.to(element, {
      y,
      ease: "none",
      scrollTrigger: {
        trigger: trigger || element.closest("section"),
        start,
        end,
        scrub,
      },
    });
  }

  // =========================================================
  // PUBLIC API
  // =========================================================
  return {
    prefersReducedMotion,
    exists,
    responsive,
    setReducedMotion,
    textReveal,
    fadeUp,
    sectionLabel,
    cards,
    scaleIn,
    slideIn,
    lineScale,
    counter,
    parallax,
  };
})();
