(function () {
  "use strict";

  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    gsap.registerPlugin(ScrollTrigger);
  } else {
    console.warn("Animations: GSAP or ScrollTrigger not loaded.");
    return;
  }

  function elementExists(selector) {
    return document.querySelector(selector) !== null;
  }

  const BREAKPOINT_MOBILE = 768;

  function isMobile() {
    return window.innerWidth < BREAKPOINT_MOBILE;
  }
  const DEFAULT_ANIMATION_OPTIONS = {
    duration: 0.8,
    delay: 0,
    ease: "power2.out",
    start: "top 80%",
    toggleActions: "play none none none",
    once: true,
  };

  function prefersReducedMotion() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  function getDataNumber(element, name, fallback) {
    const value = element.dataset[name];
    if (value === undefined || value === "") return fallback;

    const parsed = Number(value);
    return Number.isNaN(parsed) ? fallback : parsed;
  }

  function getDataBoolean(element, name, fallback = false) {
    const value = element.dataset[name];
    if (value === undefined || value === "") return fallback;
    return value === "true" || value === "1" || value === "yes";
  }

  function resolveAnimationTrigger(element) {
    const trigger = element.dataset.trigger;

    if (!trigger || trigger === "self") return element;
    if (trigger === "parent") return element.parentElement || element;
    if (trigger === "closest-section") return element.closest("section") || element;

    return document.querySelector(trigger) || element;
  }

  function showAnimationTargets(targets) {
    gsap.set(targets, {
      clearProps: "all",
      opacity: 1,
      x: 0,
      y: 0,
      scale: 1,
    });
  }

  function buildScrollTrigger(element, options = {}) {
    if (options.noScroll) return undefined;

    const trigger = options.trigger || resolveAnimationTrigger(element);

    return {
      trigger,
      start: options.start || DEFAULT_ANIMATION_OPTIONS.start,
      end: options.end || undefined,
      scrub: options.scrub || false,
      toggleActions:
        options.toggleActions || DEFAULT_ANIMATION_OPTIONS.toggleActions,
      once: options.once ?? DEFAULT_ANIMATION_OPTIONS.once,
    };
  }

  function getAnimationOptions(element) {
    const scrubValue = element.dataset.scrub;
    const scrub = scrubValue === undefined || scrubValue === ""
      ? false
      : Number(scrubValue) || true;

    return {
      type: element.dataset.animate,
      duration: getDataNumber(
        element,
        "duration",
        DEFAULT_ANIMATION_OPTIONS.duration,
      ),
      delay: getDataNumber(element, "delay", DEFAULT_ANIMATION_OPTIONS.delay),
      ease: element.dataset.ease || DEFAULT_ANIMATION_OPTIONS.ease,
      start: element.dataset.start || DEFAULT_ANIMATION_OPTIONS.start,
      end: element.dataset.end || undefined,
      stagger: getDataNumber(element, "stagger", 0),
      scrub,
      once: getDataBoolean(element, "once", DEFAULT_ANIMATION_OPTIONS.once),
      toggleActions:
        element.dataset.toggleActions || DEFAULT_ANIMATION_OPTIONS.toggleActions,
      disableMobile: getDataBoolean(element, "disableMobile", false),
      minWidth: getDataNumber(element, "minWidth", 0),
      noScroll: getDataBoolean(element, "noScroll", false),
    };
  }

  function getAnimationPreset(element, type) {
    const x = getDataNumber(element, "x", null);
    const y = getDataNumber(element, "y", null);
    const scale = getDataNumber(element, "scale", null);

    const presets = {
      fade: { from: { opacity: 0 }, to: { opacity: 1 } },
      "fade-up": { from: { opacity: 0, y: y ?? 30 }, to: { opacity: 1, y: 0 } },
      "fade-down": { from: { opacity: 0, y: y ?? -30 }, to: { opacity: 1, y: 0 } },
      "fade-left": { from: { opacity: 0, x: x ?? 40 }, to: { opacity: 1, x: 0 } },
      "fade-right": { from: { opacity: 0, x: x ?? -40 }, to: { opacity: 1, x: 0 } },
      "scale-in": { from: { opacity: 0, scale: scale ?? 0.9 }, to: { opacity: 1, scale: 1 } },
      "line-x": { from: { scaleX: 0, transformOrigin: "left center" }, to: { scaleX: 1 } },
      "line-y": { from: { scaleY: 0, transformOrigin: "top center" }, to: { scaleY: 1 } },
      "parallax-y": { from: {}, to: { y: y ?? -60 } },
      "parallax-scale": { from: {}, to: { scale: scale ?? 1.08 } },
    };

    return presets[type];
  }

  function runGenericTween(targets, element, options, preset) {
    gsap.fromTo(targets, preset.from, {
      ...preset.to,
      duration: options.duration,
      delay: options.delay,
      ease: options.scrub ? "none" : options.ease,
      overwrite: true,
      scrollTrigger: buildScrollTrigger(element, options),
    });
  }

  function initTextRevealAnimation(element, options) {
    const targets = element.matches(".text-reveal-inner")
      ? [element]
      : element.querySelectorAll(".text-reveal-inner");

    if (!targets.length) return;

    if (prefersReducedMotion()) {
      gsap.set(targets, { clearProps: "all", y: "0%" });
      return;
    }

    gsap.fromTo(
      targets,
      { y: element.dataset.fromY || "110%" },
      {
        y: element.dataset.toY || "0%",
        duration: options.duration,
        delay: options.delay,
        stagger: options.stagger || 0.12,
        ease: options.ease || "power3.out",
        overwrite: true,
        scrollTrigger: buildScrollTrigger(element, options),
      },
    );
  }

  function initStaggerAnimation(element, options, presetName) {
    const targetSelector = element.dataset.staggerTarget;
    const targets = targetSelector
      ? element.querySelectorAll(targetSelector)
      : element.children;

    if (!targets.length) return;

    if (prefersReducedMotion()) {
      showAnimationTargets(targets);
      return;
    }

    const preset = getAnimationPreset(element, presetName);
    if (!preset) return;

    gsap.fromTo(targets, preset.from, {
      ...preset.to,
      duration: options.duration,
      delay: options.delay,
      stagger: options.stagger || 0.1,
      ease: options.ease,
      overwrite: true,
      scrollTrigger: buildScrollTrigger(element, options),
    });
  }

  function initDataAnimation(element) {
    const options = getAnimationOptions(element);
    if (!options.type) return;

    if (options.disableMobile && isMobile()) {
      showAnimationTargets(element);
      return;
    }

    if (options.minWidth && window.innerWidth < options.minWidth) {
      showAnimationTargets(element);
      return;
    }

    if (options.type === "text-reveal") {
      initTextRevealAnimation(element, options);
      return;
    }

    if (options.type.startsWith("stagger-")) {
      initStaggerAnimation(element, options, options.type.replace("stagger-", ""));
      return;
    }

    if (prefersReducedMotion()) {
      showAnimationTargets(element);
      return;
    }

    const preset = getAnimationPreset(element, options.type);
    if (!preset) return;

    runGenericTween(element, element, options, preset);
  }

  function initDataAnimations() {
    document.querySelectorAll("[data-animate]").forEach(initDataAnimation);
  }

  const activeSliders = new Map();

  function initInfiniteSlider(trackSelector, wrapperSelector, options = {}) {
    const {
      duration = 50,
      direction = 'left',
      enableOnMobile = false,
    } = options;

    if (activeSliders.has(trackSelector)) {
      console.warn(`Slider ${trackSelector} already initialized`);
      return activeSliders.get(trackSelector);
    }

    if (!enableOnMobile && isMobile()) {
      return null;
    }

    const sliderTrack = document.querySelector(trackSelector);
    const sliderWrapper = document.querySelector(wrapperSelector);

    if (!sliderTrack || !sliderWrapper) {
      console.warn(`Slider elements not found: ${trackSelector}, ${wrapperSelector}`);
      return null;
    }

    const cards = sliderTrack.children;
    if (cards.length === 0) {
      console.warn(`No cards found in ${trackSelector}`);
      return null;
    }

    const originalCards = Array.from(cards);
    originalCards.forEach((card) => {
      const clone = card.cloneNode(true);
      sliderTrack.appendChild(clone);
    });

    sliderTrack.style.willChange = "transform";

    const totalWidth = sliderTrack.scrollWidth / 2;

    const sliderTl = gsap.timeline({
      repeat: -1,
      defaults: { ease: "none" },
      onRepeat: () => {
        gsap.set(sliderTrack, { x: 0 });
      }
    });

    const targetX = direction === 'left' ? -totalWidth : totalWidth;
    sliderTl.to(sliderTrack, {
      x: targetX,
      duration: duration,
      ease: "none",
    });

    const handleMouseEnter = () => sliderTl.pause();
    const handleMouseLeave = () => sliderTl.play();

    sliderWrapper.addEventListener("mouseenter", handleMouseEnter, { passive: true });
    sliderWrapper.addEventListener("mouseleave", handleMouseLeave, { passive: true });

    const sliderInstance = {
      timeline: sliderTl,
      track: sliderTrack,
      wrapper: sliderWrapper,
      eventListeners: [
        { element: sliderWrapper, event: 'mouseenter', handler: handleMouseEnter },
        { element: sliderWrapper, event: 'mouseleave', handler: handleMouseLeave },
      ],
      cleanup: function() {
        this.timeline.kill();
        
        this.eventListeners.forEach(({ element, event, handler }) => {
          element.removeEventListener(event, handler);
        });
        
        const allCards = Array.from(this.track.children);
        const cardsToRemove = allCards.slice(originalCards.length);
        cardsToRemove.forEach(card => card.remove());
        
        this.track.style.willChange = '';
        gsap.set(this.track, { x: 0, clearProps: 'transform' });
        
        activeSliders.delete(trackSelector);
      }
    };

    activeSliders.set(trackSelector, sliderInstance);

    return sliderInstance;
  }

  function cleanupAllSliders() {
    activeSliders.forEach((slider) => slider.cleanup());
    activeSliders.clear();
  }

  function cleanupAllScrollTriggers() {
    ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
  }

  function cleanupAllAnimations() {
    cleanupAllSliders();
    cleanupAllScrollTriggers();
  }

  window.addEventListener('beforeunload', cleanupAllAnimations);

  // =========================================================
  // HERO ENTRANCE ANIMATION
  // =========================================================

  function initHeroAnimations() {
    const hero = document.querySelector("#hero");
    if (!hero) return;

    if (elementExists(".hero__video")) {
      gsap.set(".hero__video", { opacity: 0 });
    }
    if (elementExists(".hero__overlay")) {
      gsap.set(".hero__overlay", { opacity: 0 });
    }

    if (elementExists(".hero__accent-bar")) {
      gsap.set(".hero__accent-bar", {
        opacity: 0,
        scaleY: 0,
        transformOrigin: "top center",
      });
    }

    if (elementExists(".hero__eyebrow-line")) {
      gsap.set(".hero__eyebrow-line", { width: 0 });
    }

    if (elementExists(".hero__eyebrow .text-reveal-inner")) {
      gsap.set(".hero__eyebrow .text-reveal-inner", { y: "110%" });
    }

    const heroWords = document.querySelectorAll("[data-hero-word]");
    if (heroWords.length) {
      gsap.set(heroWords, { y: "110%" });
    }

    const fadeUps = document.querySelectorAll(
      "[data-hero-sub], [data-hero-ctas], [data-hero-trust]",
    );
    if (fadeUps.length) {
      gsap.set(fadeUps, { opacity: 0, y: 30 });
    }

    // =========================================================
    // Master Entrance Timeline
    // =========================================================
    const heroTl = gsap.timeline({ delay: 0.3 });

    if (elementExists(".hero__video")) {
      heroTl.fromTo(
        ".hero__video",
        { opacity: 0 },
        { opacity: 1, duration: 1.5, ease: "power2.out", overwrite: true },
        0,
      );
    }

    if (elementExists(".hero__overlay")) {
      heroTl.fromTo(
        ".hero__overlay",
        { opacity: 0 },
        { opacity: 1, duration: 1.0, ease: "power2.inOut", overwrite: true },
        "<",
      );
    }

    if (elementExists(".hero__accent-bar")) {
      heroTl.fromTo(
        ".hero__accent-bar",
        { opacity: 0, scaleY: 0 },
        {
          opacity: 1,
          scaleY: 1,
          duration: 0.8,
          ease: "power3.out",
          transformOrigin: "top center",
          overwrite: true,
        },
        0.6,
      );
    }

    if (elementExists(".hero__eyebrow-line")) {
      heroTl.fromTo(
        ".hero__eyebrow-line",
        { width: "0px" },
        { width: "40px", duration: 0.6, ease: "power2.out", overwrite: true },
        0.8,
      );
    }

    if (elementExists(".hero__eyebrow .text-reveal-inner")) {
      heroTl.fromTo(
        ".hero__eyebrow .text-reveal-inner",
        { y: "110%" },
        { y: "0%", duration: 0.7, ease: "power3.out", overwrite: true },
        1.0,
      );
    }

    if (heroWords.length) {
      heroTl.fromTo(
        heroWords,
        { y: "110%" },
        {
          y: "0%",
          duration: 0.9,
          stagger: 0.12,
          ease: "power3.out",
          overwrite: true,
        },
        1.2,
      );
    }

    if (elementExists("[data-hero-sub]")) {
      heroTl.fromTo(
        "[data-hero-sub]",
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: "power2.out",
          overwrite: true,
        },
        1.7,
      );
    }

    if (elementExists("[data-hero-ctas]")) {
      heroTl.fromTo(
        "[data-hero-ctas]",
        { opacity: 0, y: 24 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: "power2.out",
          overwrite: true,
        },
        1.9,
      );
    }

    if (elementExists("[data-hero-trust]")) {
      heroTl.fromTo(
        "[data-hero-trust]",
        { opacity: 0, y: 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          overwrite: true,
        },
        2.1,
      );
    }

    return heroTl;
  }

  // =========================================================
  // HERO SCROLL ANIMATIONS
  // =========================================================

  function initHeroScrollAnimations() {
    const hero = document.querySelector("#hero");
    if (!hero) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;
    const isMobile = window.innerWidth < 768;

    let contentY = isMobile ? -40 : -80;
    let videoScale = 1.08;
    let grainY = -30;

    if (prefersReducedMotion) {
      contentY *= 0.5;
      videoScale = 1.03;
      grainY *= 0.5;
    }
    if (isMobile) {
      grainY = 0; 
    }

    // ---------------------------------------------------------
    // SCROLL ANIMATION 1
    // ---------------------------------------------------------
    if (elementExists(".hero__content")) {
      gsap.to(".hero__content", {
        y: contentY,
        ease: "none",
        scrollTrigger: {
          trigger: "#hero",
          start: "top top",
          end: "bottom top",
          scrub: 1.5,
        },
      });

      gsap.to(".hero__content", {
        opacity: 0,
        ease: "none",
        scrollTrigger: {
          trigger: "#hero",
          start: "top top",
          end: "center top",
          scrub: true,
        },
      });
    }

    // ---------------------------------------------------------
    // SCROLL ANIMATION 3 | Video subtle scale (parallax)
    // ---------------------------------------------------------
    if (
      elementExists(".hero__video-fallback") ||
      elementExists(".hero__video")
    ) {
      gsap.to([".hero__video-fallback", ".hero__video"], {
        scale: videoScale,
        ease: "none",
        transformOrigin: "center center",
        scrollTrigger: {
          trigger: "#hero",
          start: "top top",
          end: "bottom top",
          scrub: 2,
        },
      });
    }

    // ---------------------------------------------------------
    // SCROLL ANIMATION 4 | Grain texture parallax
    // ---------------------------------------------------------
    if (grainY !== 0 && elementExists(".hero__grain")) {
      gsap.to(".hero__grain", {
        y: grainY,
        ease: "none",
        scrollTrigger: {
          trigger: "#hero",
          start: "top top",
          end: "bottom top",
          scrub: 1,
        },
      });
    }
  }

  // =========================================================
  // Numbers Section Animations
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
    const numbersSection = document.querySelector("#numbers");
    if (!numbersSection) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          "#numbers .text-reveal-inner",
          "#numbers .fade-up",
          ".numbers__section-divider-line",
          ".numbers__card-line",
          ".numbers__header-divider",
        ],
        { clearProps: "all", opacity: 1 },
      );

      gsap.set("#numbers .text-reveal-inner", { y: "0%" });

      const cards = document.querySelectorAll(".numbers__card");
      cards.forEach((card) => {
        const target = parseInt(card.getAttribute("data-counter-target"), 10);
        const counterEl = card.querySelector("[data-counter]");
        if (!isNaN(target) && counterEl) {
          counterEl.textContent = target.toLocaleString("en-US");
        }
      });
      return;
    }

    ScrollTrigger.matchMedia({
      "(min-width: 769px)": function () {
        const headingLines = numbersSection.querySelectorAll(
          ".numbers__heading-line .text-reveal-inner",
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
                trigger: "#numbers",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const sectionLabel = numbersSection.querySelector(".section-label");
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
                trigger: "#numbers",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const dividerLine = numbersSection.querySelector(
          ".numbers__section-divider-line",
        );
        if (dividerLine) {
          gsap.fromTo(
            dividerLine,
            { scaleX: 0, transformOrigin: "left center" },
            {
              scaleX: 1,
              duration: 1.2,
              ease: "power2.inOut",
              scrollTrigger: {
                trigger: ".numbers__section-divider",
                start: "top 85%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const numbersContext =
          numbersSection.querySelector(".numbers__context");
        if (numbersContext) {
          gsap.fromTo(
            numbersContext,
            { opacity: 0, y: 30 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".numbers__context",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const cards = numbersSection.querySelectorAll(".numbers__card");
        if (cards.length) {
          gsap.fromTo(
            cards,
            { opacity: 0, y: 40 },
            {
              opacity: 1,
              y: 0,
              duration: 0.7,
              stagger: 0.1,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".numbers__grid",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );

          const cardLines = numbersSection.querySelectorAll(
            ".numbers__card-line",
          );
          if (cardLines.length) {
            gsap.fromTo(
              cardLines,
              { scaleX: 0, transformOrigin: "left center" },
              {
                scaleX: 1,
                duration: 0.8,
                stagger: 0.1,
                ease: "power2.inOut",
                delay: 0.3,
                scrollTrigger: {
                  trigger: ".numbers__grid",
                  start: "top 75%",
                  toggleActions: "play none none none",
                },
              },
            );
          }
        }

        const counterCards = document.querySelectorAll(".numbers__card");
        counterCards.forEach((card) => {
          const targetAttr = card.getAttribute("data-counter-target");
          const target = parseInt(targetAttr, 10);
          const counterEl = card.querySelector("[data-counter]");

          if (isNaN(target) || !counterEl) return;

          const duration = getCounterDuration(target);

          ScrollTrigger.create({
            trigger: card,
            start: "top 85%",
            once: true,
            onEnter: () => animateCounter(counterEl, target, duration),
          });
        });

        const headerDivider = numbersSection.querySelector(
          ".numbers__header-divider",
        );
        if (headerDivider) {
          gsap.fromTo(
            headerDivider,
            { scaleY: 0, opacity: 0, transformOrigin: "top center" },
            {
              scaleY: 1,
              opacity: 1,
              duration: 1.0,
              ease: "power2.out",
              delay: 0.4,
              scrollTrigger: {
                trigger: ".numbers__header",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }
      },

      "(max-width: 768px)": function () {
        const headingLines = numbersSection.querySelectorAll(
          ".numbers__heading-line .text-reveal-inner",
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
                trigger: "#numbers",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const sectionLabel = numbersSection.querySelector(".section-label");
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
                trigger: "#numbers",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const dividerLine = numbersSection.querySelector(
          ".numbers__section-divider-line",
        );
        if (dividerLine) {
          gsap.fromTo(
            dividerLine,
            { scaleX: 0, transformOrigin: "left center" },
            {
              scaleX: 1,
              duration: 1.2,
              ease: "power2.inOut",
              scrollTrigger: {
                trigger: ".numbers__section-divider",
                start: "top 85%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const numbersContext =
          numbersSection.querySelector(".numbers__context");
        if (numbersContext) {
          gsap.fromTo(
            numbersContext,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".numbers__context",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const cards = numbersSection.querySelectorAll(".numbers__card");
        if (cards.length) {
          gsap.fromTo(
            cards,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.6,
              stagger: 0.06,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".numbers__grid",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );

          const cardLines = numbersSection.querySelectorAll(
            ".numbers__card-line",
          );
          if (cardLines.length) {
            gsap.fromTo(
              cardLines,
              { scaleX: 0, transformOrigin: "left center" },
              {
                scaleX: 1,
                duration: 0.6,
                stagger: 0.06,
                ease: "power2.inOut",
                delay: 0.2,
                scrollTrigger: {
                  trigger: ".numbers__grid",
                  start: "top 75%",
                  toggleActions: "play none none none",
                },
              },
            );
          }
        }

        const counterCards = document.querySelectorAll(".numbers__card");
        counterCards.forEach((card) => {
          const targetAttr = card.getAttribute("data-counter-target");
          const target = parseInt(targetAttr, 10);
          const counterEl = card.querySelector("[data-counter]");

          if (isNaN(target) || !counterEl) return;

          const duration = getCounterDuration(target);

          ScrollTrigger.create({
            trigger: card,
            start: "top 85%",
            once: true,
            onEnter: () => animateCounter(counterEl, target, duration),
          });
        });
      },
    });
  }

  // =========================================================
  // What Is Maverick Section Animations
  // =========================================================

function initWIMAnimations() {
  const section = document.querySelector("#what-is-maverick");
  if (!section) return;

  const pinWrapper     = section.querySelector(".wim__pin-wrapper");
  const headingWrapper = section.querySelector(".wim__heading-wrapper");
  const label          = section.querySelector(".wim__label");
  const bgImage        = section.querySelector(".wim__bg-image");
  const finalEl        = section.querySelector(".wim__final");
  const statements     = gsap.utils.toArray(
                           section.querySelectorAll(".wim__statement")
                         );
  const totalStatements = statements.length;

  if (!pinWrapper || !headingWrapper || totalStatements === 0) {
    console.warn("WIM: Required elements not found, skipping animations.");
    return;
  }

  ScrollTrigger.getAll().forEach((st) => {
    if (st.trigger === section) st.kill();
  });

  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;

  if (prefersReducedMotion) {
    gsap.set([headingWrapper, label, finalEl, pinWrapper], {
      clearProps: "all",
      opacity: 1,
      y: 0,
    });
    gsap.set(statements, { clearProps: "all", opacity: 1 });
    statements.forEach((stmt) => {
      const txt = stmt.querySelector(".wim__statement-text");
      if (txt) gsap.set(txt, { clearProps: "all", y: "0%" });
    });
    if (bgImage) gsap.set(bgImage, { clearProps: "all" });
    return;
  }

  const isMobile = window.innerWidth <= 768;

  const cfg = isMobile
    ? {
        headingInitY   : "-8vh",
        headingSettleY : "-2vh",
        entryStart     : "top 70%",
        entryEnd       : "top 20%",
        pinEnd         : "+=110%",
        bgScale        : 1.08,
        labelOpacity   : 1,
        scrubEntry     : 0.8,
        scrubPin       : 1,
      }
    : {
        headingInitY   : "-15vh",
        headingSettleY : "-3vh",
        entryStart     : "top 65%",
        entryEnd       : "top 15%",
        pinEnd         : "+=140%",
        bgScale        : 1.12,
        labelOpacity   : 0.9,
        scrubEntry     : 1,
        scrubPin       : 1.2,
      };

  const STMT_START    = 0.05;
  const STMT_ZONE_END = 0.62;
  const FINAL_POS     = 0.66;
  const FADE_START    = 0.88;
  const FADE_DUR      = 0.12;

  const stmtZone = STMT_ZONE_END - STMT_START;
  const STMT_GAP = totalStatements > 1
    ? stmtZone / (totalStatements - 1)
    : 0;

  gsap.set(headingWrapper, { y: cfg.headingInitY, opacity: 0 });
  gsap.set(label,          { y: 10, opacity: 0 });
  gsap.set(statements,     { opacity: 0 });
  gsap.set(finalEl,        { y: 12, opacity: 0 });
  gsap.set(pinWrapper,     { opacity: 1 });

  statements.forEach((stmt) => {
    const txt = stmt.querySelector(".wim__statement-text");
    if (txt) gsap.set(txt, { y: "100%" });
  });

  if (bgImage) gsap.set(bgImage, { scale: 1 });

  const entryTl = gsap.timeline({
    scrollTrigger: {
      trigger : section,
      start   : cfg.entryStart,
      end     : cfg.entryEnd,
      scrub   : cfg.scrubEntry,
    },
  });

  entryTl
    .to(headingWrapper, {
      y        : "0vh",
      opacity  : 1,
      duration : 1,
      ease     : "power2.out",
    })
    .to(
      label,
      {
        y        : 0,
        opacity  : cfg.labelOpacity,
        duration : 0.7,
        ease     : "power2.out",
      },
      "-=0.5"
    );

  const wimTl = gsap.timeline({
    scrollTrigger: {
      trigger             : section,
      start               : "top top",
      end                 : cfg.pinEnd,
      scrub               : cfg.scrubPin,
      pin                 : pinWrapper,
      anticipatePin       : 1,
      pinSpacing          : true,
      invalidateOnRefresh : true,
    },
  });

  if (bgImage) {
    wimTl.to(
      bgImage,
      {
        scale    : cfg.bgScale,
        duration : 1,
        ease     : "none",
      },
      0 
    );
  }

  wimTl.to(
    headingWrapper,
    {
      y        : cfg.headingSettleY,
      duration : 0.3,
      ease     : "power1.inOut",
    },
    0
  );

  statements.forEach((stmt, index) => {
    const stmtText = stmt.querySelector(".wim__statement-text");
    const pos      = STMT_START + index * STMT_GAP;

    wimTl.to(
      stmt,
      {
        opacity  : 1,
        duration : 0.15,
        ease     : "power2.out",
      },
      pos
    );

    if (stmtText) {
      wimTl.to(
        stmtText,
        {
          y        : "0%",
          duration : 0.18,
          ease     : "power3.out",
        },
        pos
      );
    }
  });

  wimTl.to(
    finalEl,
    {
      opacity  : 1,
      y        : 0,
      duration : 0.08,
      ease     : "power2.out",
    },
    FINAL_POS
  );

  wimTl.to(
    pinWrapper,
    {
      opacity  : 0,
      duration : FADE_DUR, 
      ease     : "power1.inOut", 
    },
    FADE_START 
  );
}

  // =========================================================
  // Who We Are Section Animations
  // =========================================================

  function initWWAAnimations() {
    if (!elementExists("#who-we-are")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          ".wwa__heading-line .text-reveal-inner",
          ".wwa__body",
          ".wwa__stats",
          ".wwa__cta",
          ".wwa__image-accent",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set(".wwa__heading-line .text-reveal-inner", { y: "0%" });
      return;
    }

    ScrollTrigger.matchMedia({
      "(min-width: 769px)": function () {
        const sectionLabel = document.querySelector(
          "#who-we-are .section-label",
        );
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
                trigger: "#who-we-are",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const headingLines = document.querySelectorAll(
          ".wwa__heading-line .text-reveal-inner",
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
                trigger: "#who-we-are",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const bodyText = document.querySelector(".wwa__body");
        if (bodyText) {
          gsap.fromTo(
            bodyText,
            { opacity: 0, y: 30 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__body",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const stats = document.querySelector(".wwa__stats");
        if (stats) {
          gsap.fromTo(
            stats,
            { opacity: 0, y: 30 },
            {
              opacity: 1,
              y: 0,
              duration: 0.7,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__stats",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const cta = document.querySelector(".wwa__cta");
        if (cta) {
          gsap.fromTo(
            cta,
            { opacity: 0, y: 24 },
            {
              opacity: 1,
              y: 0,
              duration: 0.6,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__cta",
                start: "top 85%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const image = document.querySelector(".wwa__image");
        if (image) {
          gsap.to(image, {
            y: -40,
            ease: "none",
            scrollTrigger: {
              trigger: ".wwa__image-col",
              start: "top bottom",
              end: "bottom top",
              scrub: 1.5,
            },
          });
        }

        const imageAccent = document.querySelector(".wwa__image-accent");
        if (imageAccent) {
          gsap.fromTo(
            imageAccent,
            { opacity: 0 },
            {
              opacity: 1,
              duration: 0.6,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__image-wrapper",
                start: "top 70%",
                toggleActions: "play none none none",
              },
            },
          );
        }
      },

      "(max-width: 768px)": function () {
        const sectionLabel = document.querySelector(
          "#who-we-are .section-label",
        );
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
                trigger: "#who-we-are",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const headingLines = document.querySelectorAll(
          ".wwa__heading-line .text-reveal-inner",
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
                trigger: "#who-we-are",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const bodyText = document.querySelector(".wwa__body");
        if (bodyText) {
          gsap.fromTo(
            bodyText,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__body",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const stats = document.querySelector(".wwa__stats");
        if (stats) {
          gsap.fromTo(
            stats,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.7,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__stats",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const cta = document.querySelector(".wwa__cta");
        if (cta) {
          gsap.fromTo(
            cta,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.6,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__cta",
                start: "top 85%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const image = document.querySelector(".wwa__image");
        if (image) {
          gsap.to(image, {
            y: -20,
            ease: "none",
            scrollTrigger: {
              trigger: ".wwa__image-col",
              start: "top bottom",
              end: "bottom top",
              scrub: 1,
            },
          });
        }

        const imageAccent = document.querySelector(".wwa__image-accent");
        if (imageAccent) {
          gsap.fromTo(
            imageAccent,
            { opacity: 0 },
            {
              opacity: 1,
              duration: 0.6,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwa__image-wrapper",
                start: "top 70%",
                toggleActions: "play none none none",
              },
            },
          );
        }
      },
    });
  }

  function initCEOAnimations() {
    if (!elementExists("#ceo-message")) return;
    
        const ceoImageAccent = document.querySelector(".ceo__image");
        if (ceoImageAccent) {
          gsap.fromTo(
            ceoImageAccent,
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

    const headingLines = document.querySelectorAll(
      "#ceo-message .text-reveal-inner"
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
        }
      );
    }

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: "#ceo-message",
        start: "top 75%",
        toggleActions: "play none none none"
      }
    });

    tl.fromTo(
      ".ceo__image-wrapper",
      {
        opacity: 0,
        scale: 0.95
      },
      {
        opacity: 1,
        scale: 1,
        duration: 0.9,
        ease: "power2.out"
      }
    );

    tl.fromTo(
      [
        ".section-label",
        ".ceo__quote",
        ".ceo__body",
        ".ceo__signature"
      ],
      {
        opacity: 0,
        y: 40
      },
      {
        opacity: 1,
        y: 0,
        stagger: 0.15,
        duration: 0.8,
        ease: "power2.out"
      },
      "-=0.4"
    );
  }
  
  // =========================================================
  // What We Do Section Animations
  // =========================================================

  function initWWDAnimations() {
    if (!elementExists("#what-we-do")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          ".wwd__heading-line .text-reveal-inner",
          ".wwd__context",
          ".wwd__card",
          ".wwd__card-index",
          ".wwd__card-item",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set(".wwd__heading-line .text-reveal-inner", { y: "0%" });
      return;
    }

    ScrollTrigger.matchMedia({
      "(min-width: 769px)": function () {
        const sectionLabel = document.querySelector(
          "#what-we-do .section-label",
        );
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
                trigger: "#what-we-do",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const headingLines = document.querySelectorAll(
          ".wwd__heading-line .text-reveal-inner",
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
                trigger: "#what-we-do",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const contextText = document.querySelector(".wwd__context");
        if (contextText) {
          gsap.fromTo(
            contextText,
            { opacity: 0, y: 30 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwd__context",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const cards = document.querySelectorAll(".wwd__card");
        if (cards.length) {
          gsap.fromTo(
            cards,
            { opacity: 0, y: 40 },
            {
              opacity: 1,
              y: 0,
              duration: 0.7,
              stagger: 0.15,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwd__grid",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );

          const cardIndexes = document.querySelectorAll(".wwd__card-index");
          if (cardIndexes.length) {
            gsap.fromTo(
              cardIndexes,
              { opacity: 0 },
              {
                opacity: 1,
                duration: 0.5,
                stagger: 0.15,
                delay: 0.3,
                ease: "power2.out",
                scrollTrigger: {
                  trigger: ".wwd__grid",
                  start: "top 75%",
                  toggleActions: "play none none none",
                },
              },
            );
          }

          cards.forEach((card) => {
            const items = card.querySelectorAll(".wwd__card-item");
            if (items.length) {
              gsap.fromTo(
                items,
                { opacity: 0, x: -10 },
                {
                  opacity: 1,
                  x: 0,
                  duration: 0.4,
                  stagger: 0.08,
                  ease: "power2.out",
                  scrollTrigger: {
                    trigger: card,
                    start: "top 70%",
                    toggleActions: "play none none none",
                  },
                },
              );
            }
          });
        }
      },

      "(max-width: 768px)": function () {
        const sectionLabel = document.querySelector(
          "#what-we-do .section-label",
        );
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
                trigger: "#what-we-do",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const headingLines = document.querySelectorAll(
          ".wwd__heading-line .text-reveal-inner",
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
                trigger: "#what-we-do",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const contextText = document.querySelector(".wwd__context");
        if (contextText) {
          gsap.fromTo(
            contextText,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwd__context",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const cards = document.querySelectorAll(".wwd__card");
        if (cards.length) {
          gsap.fromTo(
            cards,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.6,
              stagger: 0.1,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".wwd__grid",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );

          const cardIndexes = document.querySelectorAll(".wwd__card-index");
          if (cardIndexes.length) {
            gsap.fromTo(
              cardIndexes,
              { opacity: 0 },
              {
                opacity: 1,
                duration: 0.5,
                stagger: 0.1,
                delay: 0.2,
                ease: "power2.out",
                scrollTrigger: {
                  trigger: ".wwd__grid",
                  start: "top 75%",
                  toggleActions: "play none none none",
                },
              },
            );
          }

          cards.forEach((card) => {
            const items = card.querySelectorAll(".wwd__card-item");
            if (items.length) {
              gsap.fromTo(
                items,
                { opacity: 0, x: -8 },
                {
                  opacity: 1,
                  x: 0,
                  duration: 0.4,
                  stagger: 0.06,
                  ease: "power2.out",
                  scrollTrigger: {
                    trigger: card,
                    start: "top 70%",
                    toggleActions: "play none none none",
                  },
                },
              );
            }
          });
        }
      },
    });
  }

  // =========================================================
  // How We Do It Section Animations
  // =========================================================

  function initHWDIAnimations() {
    if (!elementExists("#how-we-do-it")) return;

    gsap.set(".hwdi__heading-line .text-reveal-inner", { y: "110%" });
    gsap.set(".hwdi__subtitle", { opacity: 0 });
    gsap.set(".hwdi__step", { opacity: 0 });
    gsap.set(".hwdi__step-number", { scale: 0.7, opacity: 0 });
    gsap.set(".hwdi__step-accent", {
      scaleY: 0,
      transformOrigin: "top center",
    });
    gsap.set(".hwdi__connector-line", {
      scaleX: 0,
      transformOrigin: "left center",
    });

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          ".hwdi__heading-line .text-reveal-inner",
          ".hwdi__subtitle",
          ".hwdi__step",
          ".hwdi__step-number",
          ".hwdi__step-accent",
          ".hwdi__connector-line",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set(".hwdi__heading-line .text-reveal-inner", { y: "0%" });
      gsap.set(".hwdi__step-number", { scale: 1 });
      gsap.set(".hwdi__step-accent", { scaleY: 1 });
      gsap.set(".hwdi__connector-line", { scaleX: 1 });
      return;
    }

    ScrollTrigger.matchMedia({
      "(min-width: 769px)": function () {
        const sectionLabel = document.querySelector(
          "#how-we-do-it .section-label",
        );
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
                trigger: "#how-we-do-it",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const headingLines = document.querySelectorAll(
          ".hwdi__heading-line .text-reveal-inner",
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
                trigger: "#how-we-do-it",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const subtitle = document.querySelector(".hwdi__subtitle");
        if (subtitle) {
          gsap.fromTo(
            subtitle,
            { opacity: 0, y: 20 },
            {
              opacity: 1,
              y: 0,
              duration: 0.7,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".hwdi__subtitle",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const steps = document.querySelectorAll(".hwdi__step");
        if (steps.length) {
          gsap.fromTo(
            steps,
            { opacity: 0, y: 40 },
            {
              opacity: 1,
              y: 0,
              duration: 0.8,
              stagger: 0.2,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".hwdi__steps",
                start: "top 70%",
                toggleActions: "play none none none",
              },
            },
          );

          const stepNumbers = document.querySelectorAll(".hwdi__step-number");
          if (stepNumbers.length) {
            gsap.fromTo(
              stepNumbers,
              { scale: 0.8, opacity: 0 },
              {
                scale: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.2,
                ease: "back.out(1.7)",
                scrollTrigger: {
                  trigger: ".hwdi__steps",
                  start: "top 70%",
                  toggleActions: "play none none none",
                },
              },
            );
          }

          const stepAccents = document.querySelectorAll(".hwdi__step-accent");
          if (stepAccents.length) {
            gsap.fromTo(
              stepAccents,
              { scaleY: 0 },
              {
                scaleY: 1,
                duration: 0.5,
                stagger: 0.2,
                ease: "power2.out",
                scrollTrigger: {
                  trigger: ".hwdi__steps",
                  start: "top 70%",
                  toggleActions: "play none none none",
                },
              },
            );
          }
        }

        const connectors = document.querySelectorAll(".hwdi__connector-line");
        if (connectors.length) {
          gsap.fromTo(
            connectors,
            { scaleX: 0 },
            {
              scaleX: 1,
              duration: 0.8,
              stagger: 0.15,
              ease: "power2.inOut",
              scrollTrigger: {
                trigger: ".hwdi__steps",
                start: "top 65%",
                toggleActions: "play none none none",
              },
            },
          );
        }
      },

      "(max-width: 768px)": function () {
        const sectionLabel = document.querySelector(
          "#how-we-do-it .section-label",
        );
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
                trigger: "#how-we-do-it",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const headingLines = document.querySelectorAll(
          ".hwdi__heading-line .text-reveal-inner",
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
                trigger: "#how-we-do-it",
                start: "top 75%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const subtitle = document.querySelector(".hwdi__subtitle");
        if (subtitle) {
          gsap.fromTo(
            subtitle,
            { opacity: 0, y: 16 },
            {
              opacity: 1,
              y: 0,
              duration: 0.7,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".hwdi__subtitle",
                start: "top 80%",
                toggleActions: "play none none none",
              },
            },
          );
        }

        const steps = document.querySelectorAll(".hwdi__step");
        if (steps.length) {
          gsap.fromTo(
            steps,
            { opacity: 0, y: 24 },
            {
              opacity: 1,
              y: 0,
              duration: 0.6,
              stagger: 0.15,
              ease: "power2.out",
              scrollTrigger: {
                trigger: ".hwdi__steps",
                start: "top 70%",
                toggleActions: "play none none none",
              },
            },
          );

          const stepNumbers = document.querySelectorAll(".hwdi__step-number");
          if (stepNumbers.length) {
            gsap.fromTo(
              stepNumbers,
              { scale: 0.8, opacity: 0 },
              {
                scale: 1,
                opacity: 1,
                duration: 0.5,
                stagger: 0.15,
                ease: "back.out(1.5)",
                scrollTrigger: {
                  trigger: ".hwdi__steps",
                  start: "top 70%",
                  toggleActions: "play none none none",
                },
              },
            );
          }

          const stepAccents = document.querySelectorAll(".hwdi__step-accent");
          if (stepAccents.length) {
            gsap.fromTo(
              stepAccents,
              { scaleY: 0 },
              {
                scaleY: 1,
                duration: 0.4,
                stagger: 0.15,
                ease: "power2.out",
                scrollTrigger: {
                  trigger: ".hwdi__steps",
                  start: "top 70%",
                  toggleActions: "play none none none",
                },
              },
            );
          }
        }

      },
    });
  }

  // =========================================================
  // Alumni Network Section Animations
  // =========================================================

  function initAlumniAnimations() {
    if (!elementExists("#alumni-network")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          "#alumni-network .section-label",
          ".alumni__heading-line .text-reveal-inner",
          ".alumni__subtitle",
          ".alumni__marquee-wrapper",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set(".alumni__heading-line .text-reveal-inner", { y: "0%" });
      return;
    }

    gsap.set("#alumni-network .section-label", { opacity: 0, y: 16 });
    gsap.set("#alumni-network .text-reveal-inner", { y: "110%" });
    gsap.set(".alumni__subtitle", { opacity: 0, y: 20 });
    gsap.set(".alumni__marquee-wrapper", { opacity: 0, scale: 0.97 });

    const label = document.querySelector("#alumni-network .section-label");
    if (label) {
      gsap.fromTo(
        label,
        { opacity: 0, y: 16 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#alumni-network",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const headings = document.querySelectorAll(
      "#alumni-network .text-reveal-inner",
    );
    if (headings.length) {
      gsap.fromTo(
        headings,
        { y: "110%" },
        {
          y: "0%",
          duration: 0.9,
          stagger: 0.12,
          ease: "power3.out",
          scrollTrigger: {
            trigger: "#alumni-network",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const subtitle = document.querySelector(".alumni__subtitle");
    if (subtitle) {
      gsap.fromTo(
        subtitle,
        { opacity: 0, y: 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: "power2.out",
          scrollTrigger: {
            trigger: ".alumni__subtitle",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const marquee = document.querySelector(".alumni__marquee-wrapper");
    if (marquee) {
      gsap.fromTo(
        marquee,
        { opacity: 0, scale: 0.97 },
        {
          opacity: 1,
          scale: 1,
          duration: 0.8,
          ease: "power2.out",
          scrollTrigger: {
            trigger: ".alumni__marquee-wrapper",
            start: "top 85%",
            toggleActions: "play none none none",
          },
        },
      );
    }
  }

  // =========================================================
  // Featured Programs Section Animations
  // =========================================================

  function initProgramsAnimations() {
    if (!elementExists("#featured-programs")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    gsap.set("#featured-programs .section-label", { opacity: 0, y: 16 });
    gsap.set("#featured-programs .text-reveal-inner", { y: "110%" });
    gsap.set("#featured-programs .programs__subtitle", { opacity: 0, y: 20 });

    if (prefersReducedMotion) {
      gsap.set(
        [
          "#featured-programs .section-label",
          "#featured-programs .text-reveal-inner",
          "#featured-programs .programs__subtitle",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set("#featured-programs .text-reveal-inner", { y: "0%" });
      return;
    }

    const label = document.querySelector("#featured-programs .section-label");
    if (label) {
      gsap.fromTo(
        label,
        { opacity: 0, y: 16 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#featured-programs",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const headings = document.querySelectorAll(
      "#featured-programs .text-reveal-inner",
    );
    if (headings.length) {
      gsap.fromTo(
        headings,
        { y: "100%" },
        {
          y: "0%",
          duration: 0.9,
          stagger: 0.1,
          ease: "power3.out",
          scrollTrigger: {
            trigger: "#featured-programs",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const subtitle = document.querySelector(
      "#featured-programs .programs__subtitle",
    );
    if (subtitle) {
      gsap.fromTo(
        subtitle,
        { opacity: 0, y: 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#featured-programs .programs__subtitle",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    ScrollTrigger.matchMedia({
      "(min-width: 769px)": function () {
        const track = document.querySelector(".programs__track");
        if (!track) return;

        const getScrollAmount = () => {
          return track.scrollWidth - window.innerWidth;
        };

        gsap.to(track, {
          x: () => -getScrollAmount(),
          ease: "none",
          scrollTrigger: {
            trigger: "#featured-programs",
            pin: true,
            scrub: 1,
            start: "bottom bottom",
            end: () => "+=" + getScrollAmount(),
            invalidateOnRefresh: true,
          },
        });
      },
    });
  }

  // =========================================================
  // Why Maverick Section Animations
  // =========================================================

  function initWhyMaverickAnimations() {
    if (!elementExists("#why-maverick")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    gsap.set("#why-maverick .section-label", { opacity: 0, y: 16 });
    gsap.set("#why-maverick .text-reveal-inner", { y: "110%" });
    gsap.set("#why-maverick .why__subtitle", { opacity: 0, y: 20 });
    gsap.set("#why-maverick .why__tile", { opacity: 0, y: 40 });

    if (prefersReducedMotion) {
      gsap.set(
        [
          "#why-maverick .section-label",
          "#why-maverick .text-reveal-inner",
          "#why-maverick .why__subtitle",
          "#why-maverick .why__tile",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set("#why-maverick .text-reveal-inner", { y: "0%" });
      return;
    }

    const label = document.querySelector("#why-maverick .section-label");
    if (label) {
      gsap.fromTo(
        label,
        { opacity: 0, y: 16 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#why-maverick",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const headings = document.querySelectorAll(
      "#why-maverick .text-reveal-inner",
    );
    if (headings.length) {
      gsap.fromTo(
        headings,
        { y: "110%" },
        {
          y: "0%",
          duration: 0.9,
          stagger: 0.12,
          ease: "power3.out",
          scrollTrigger: {
            trigger: "#why-maverick",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const subtitle = document.querySelector("#why-maverick .why__subtitle");
    if (subtitle) {
      gsap.fromTo(
        subtitle,
        { opacity: 0, y: 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#why-maverick .why__subtitle",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        },
      );
    }

    const tiles = document.querySelectorAll("#why-maverick .why__tile");
    if (tiles.length) {
      gsap.fromTo(
        tiles,
        { opacity: 0, y: 40 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          stagger: 0.1,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#why-maverick .why__grid",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        },
      );
    }
  }
  // =========================================================
  // Global Opportunities & Pathways Section Animations
  // =========================================================

  function initOpportunitiesAnimations() {
    if (!elementExists("#global-opportunities")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          "#global-opportunities .section-label",
          "#global-opportunities .text-reveal-inner",
          "#global-opportunities .opportunities__subtitle",
          "#global-opportunities .opportunities__column-header",
          "#global-opportunities .opportunities__item",
          "#global-opportunities .opportunities__divider",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set("#global-opportunities .text-reveal-inner", { y: "0%" });
      return;
    }

    gsap.set("#global-opportunities .section-label", { opacity: 0, y: 16 });
    gsap.set("#global-opportunities .text-reveal-inner", { y: "110%" });
    gsap.set("#global-opportunities .opportunities__subtitle", {
      opacity: 0,
      y: 20,
    });
    gsap.set("#global-opportunities .opportunities__column-header", {
      opacity: 0,
      y: 30,
    });
    gsap.set("#global-opportunities .opportunities__item", {
      opacity: 0,
      y: 24,
    });
    gsap.set("#global-opportunities .opportunities__divider", {
      scaleY: 0,
      transformOrigin: "top center",
    });

    const label = document.querySelector(
      "#global-opportunities .section-label",
    );
    if (label) {
      gsap.to(label, {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#global-opportunities",
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }

    const headingInner = document.querySelector(
      "#global-opportunities .opportunities__heading .text-reveal-inner",
    );
    if (headingInner) {
      gsap.to(headingInner, {
        y: "0%",
        duration: 0.9,
        ease: "power3.out",
        scrollTrigger: {
          trigger: "#global-opportunities",
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }

    const subtitle = document.querySelector(
      "#global-opportunities .opportunities__subtitle",
    );
    if (subtitle) {
      gsap.to(subtitle, {
        opacity: 1,
        y: 0,
        duration: 0.7,
        delay: 0.2,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#global-opportunities",
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }

    const divider = document.querySelector(
      "#global-opportunities .opportunities__divider",
    );
    if (divider) {
      gsap.to(divider, {
        scaleY: 1,
        duration: 0.8,
        ease: "power2.inOut",
        scrollTrigger: {
          trigger: "#global-opportunities .opportunities__split",
          start: "top 80%",
          toggleActions: "play none none none",
        },
      });
    }

    const columnHeaders = document.querySelectorAll(
      "#global-opportunities .opportunities__column-header",
    );
    if (columnHeaders.length) {
      gsap.to(columnHeaders, {
        opacity: 1,
        y: 0,
        duration: 0.6,
        stagger: 0.15,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#global-opportunities .opportunities__split",
          start: "top 80%",
          toggleActions: "play none none none",
        },
      });
    }

    const leftItems = document.querySelectorAll(
      "#global-opportunities .opportunities__column--left .opportunities__item",
    );
    if (leftItems.length) {
      gsap.to(leftItems, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.08,
        delay: 0.3,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#global-opportunities .opportunities__split",
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }

    const rightItems = document.querySelectorAll(
      "#global-opportunities .opportunities__column--right .opportunities__item",
    );
    if (rightItems.length) {
      gsap.to(rightItems, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.08,
        delay: 0.45,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#global-opportunities .opportunities__split",
          start: "top 75%",
          toggleActions: "play none none none",
        },
      });
    }
  }

  // =========================================================
  // University Partners Section Animations
  // =========================================================

  function initPartnersAnimations() {
    if (!elementExists("#university-partners")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        [
          "#university-partners .section-label",
          "#university-partners .text-reveal-inner",
          "#university-partners .partners__pin",
          "#university-partners .partners__detail-panel",
          "#university-partners .partners__mobile-item",
        ],
        { clearProps: "all", opacity: 1 },
      );
      gsap.set("#university-partners .text-reveal-inner", { y: "0%" });
      return;
    }

    const label = document.querySelector("#university-partners .section-label");
    const headings = document.querySelectorAll(
      "#university-partners .text-reveal-inner",
    );

    setTimeout(() => {
      const mobileItems = document.querySelectorAll(
        "#university-partners .partners__mobile-item",
      );
      if (mobileItems.length) {
        gsap.set(mobileItems, { opacity: 0, x: -20 });
        gsap.to(mobileItems, {
          opacity: 1,
          x: 0,
          duration: 0.5,
          stagger: 0.05,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#university-partners .partners__mobile-list",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        });
      }
    }, 100);

    const detailPanel = document.querySelector(
      "#university-partners .partners__detail-panel",
    );
    if (detailPanel) {
      gsap.to(detailPanel, {
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: "power2.out",
        scrollTrigger: {
          trigger: "#university-partners .partners__detail-panel",
          start: "top 85%",
          toggleActions: "play none none none",
        },
      });
    }
  }

  // =========================================================
  // Faculty Insights Section Animations
  // =========================================================

  function initHorizontalCardSectionAnimations(config) {
    const {
      sectionSelector,
      headingSelector,
      subtitleSelector,
      cardsSelector,
      cardsTriggerSelector,
      cardDuration = 0.6,
      cardStagger = 0.1,
      cardsStart = "top 75%",
    } = config;

    if (!elementExists(sectionSelector)) return;

    const animations = [
      {
        selector: `${sectionSelector} .section-label`,
        type: "fade-up",
        y: 16,
        duration: 0.6,
        trigger: sectionSelector,
      },
      {
        selector: headingSelector,
        type: "text-reveal",
        duration: 0.9,
        stagger: 0.12,
        ease: "power3.out",
        trigger: sectionSelector,
      },
      {
        selector: subtitleSelector,
        type: "fade-up",
        y: 30,
        duration: 0.8,
        trigger: sectionSelector,
      },
      {
        selector: cardsSelector,
        type: "fade-left",
        x: 40,
        duration: cardDuration,
        stagger: cardStagger,
        trigger: cardsTriggerSelector,
        start: cardsStart,
      },
    ];

    initConfiguredAnimations(animations);
  }

  function initConfiguredAnimations(animations) {
    animations.forEach((animation) => {
      const targets = document.querySelectorAll(animation.selector);
      if (!targets.length) return;

      if (prefersReducedMotion()) {
        if (animation.type === "text-reveal") {
          gsap.set(targets, { clearProps: "all", y: "0%" });
        } else {
          showAnimationTargets(targets);
        }
        return;
      }

      const triggerElement = animation.trigger
        ? document.querySelector(animation.trigger)
        : targets[0];

      if (animation.type === "text-reveal") {
        gsap.fromTo(
          targets,
          { y: animation.fromY || "110%" },
          {
            y: animation.toY || "0%",
            duration: animation.duration || 0.9,
            delay: animation.delay || 0,
            stagger: animation.stagger || 0,
            ease: animation.ease || "power3.out",
            overwrite: true,
            scrollTrigger: {
              trigger: triggerElement || targets[0],
              start: animation.start || "top 75%",
              toggleActions: animation.toggleActions || "play none none none",
            },
          },
        );
        return;
      }

      const proxy = { dataset: { x: animation.x, y: animation.y, scale: animation.scale } };
      const preset = getAnimationPreset(proxy, animation.type);
      if (!preset) return;

      gsap.fromTo(targets, preset.from, {
        ...preset.to,
        duration: animation.duration || DEFAULT_ANIMATION_OPTIONS.duration,
        delay: animation.delay || 0,
        stagger: animation.stagger || 0,
        ease: animation.ease || DEFAULT_ANIMATION_OPTIONS.ease,
        overwrite: true,
        scrollTrigger: {
          trigger: triggerElement || targets[0],
          start: animation.start || DEFAULT_ANIMATION_OPTIONS.start,
          toggleActions: animation.toggleActions || DEFAULT_ANIMATION_OPTIONS.toggleActions,
        },
      });
    });
  }

  // =========================================================
  // Faculty Insights Section Animations
  // =========================================================

  function initInsightsAnimations() {
    initHorizontalCardSectionAnimations({
      sectionSelector: "#faculty-insights",
      headingSelector: "#faculty-insights .insights__heading-line .text-reveal-inner",
      subtitleSelector: "#faculty-insights .insights__subtitle",
      cardsSelector: "#faculty-insights .insights__card",
      cardsTriggerSelector: "#faculty-insights .insights__scroll",
      cardDuration: 0.7,
      cardStagger: 0.12,
    });
  }

  // =========================================================
  // Upcoming Events Section Animations
  // =========================================================

  function initEventsAnimations() {
    initHorizontalCardSectionAnimations({
      sectionSelector: "#upcoming-events",
      headingSelector: "#upcoming-events .events__heading-line .text-reveal-inner",
      subtitleSelector: "#upcoming-events .events__subtitle",
      cardsSelector: "#upcoming-events .events__card",
      cardsTriggerSelector: "#upcoming-events .events__scroll",
      cardDuration: 0.6,
      cardStagger: 0.1,
      cardsStart: "top 80%",
    });
  }

  // =========================================================
  // Video Testimonials Section Animations
  // =========================================================

  function initTestimonialsAnimations() {
    initHorizontalCardSectionAnimations({
      sectionSelector: "#video-testimonials",
      headingSelector: "#video-testimonials .testimonials__heading-line .text-reveal-inner",
      subtitleSelector: "#video-testimonials .testimonials__subtitle",
      cardsSelector: "#video-testimonials .testimonials__card",
      cardsTriggerSelector: "#video-testimonials .testimonials__scroll",
      cardDuration: 0.6,
      cardStagger: 0.08,
    });
  }

  // =========================================================
  // Final CTA Section Animations
  // =========================================================

  function initFinalCTAAnimations() {
    if (!elementExists("#final-cta")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(
        ["#final-cta .text-reveal-inner", "#final-cta .fade-up"],
        { clearProps: "all", opacity: 1 },
      );
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

    const headingInner = document.querySelector(
      "#final-cta .text-reveal-inner",
    );
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
  // INITIALIZE
  // =========================================================

  function initFooterAnimations() {
    if (!elementExists("#footer")) return;
    
    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    
    const yearEl = document.querySelector("[data-current-year]");
    if (yearEl) {
      yearEl.textContent = new Date().getFullYear();
    }
    
    const newsletterForm = document.querySelector("[data-newsletter-form]");
    if (newsletterForm) {
      newsletterForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const input = newsletterForm.querySelector(".footer__newsletter-input");
        const btn = newsletterForm.querySelector(".footer__newsletter-btn span");
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
    
    if (prefersReducedMotion) return;
    
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
          toggleActions: "play none none none"
        }
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
          toggleActions: "play none none none"
        }
      });
    }
  }

  // =========================================================
  // ── Logo Slider Sections (Shared) ──────────────────────
  // Used by: Accreditations + Alumni Network
  // Infinite logo slider + scroll reveal animations
  // =========================================================

  function initLogoSliderSection(config) {
    const {
      sectionId,
      sliderTrackSelector,
      sliderWrapperSelector,
      cardSelector,
      fades,
    } = config;

    const sectionSelector = "#" + sectionId;
    if (!elementExists(sectionSelector)) return;

    const allAnimatedSelectors = [
      ...fades.map((f) => f.selector),
      cardSelector,
    ];

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
      gsap.set(allAnimatedSelectors, { opacity: 1, y: 0, x: 0, scale: 1 });
      return;
    }

    // Infinite slider
    initInfiniteSlider(sliderTrackSelector, sliderWrapperSelector, {
      duration: 50,
      direction: "left",
      enableOnMobile: true,
    });

    // Skip scroll-reveal animations on mobile for performance
    if (isMobile()) {
      gsap.set(allAnimatedSelectors, { opacity: 1, y: 0, x: 0, scale: 1 });
      return;
    }

    // Simple fade-up blocks (label, heading, subheading, badges/description, trust)
    fades.forEach((f) => {
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
            trigger: sectionSelector,
            start: "top 80%",
            toggleActions: "play none none none",
            once: true,
          },
        },
      );
    });

    // Card scale-in
    const cards = document.querySelectorAll(cardSelector);
    if (cards.length) {
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
            trigger: sectionSelector,
            start: "top 80%",
            toggleActions: "play none none none",
            once: true,
          },
        },
      );
    }
  }

  // =========================================================
  // ── Accreditations, Partnerships & Recognitions Section ──
  // =========================================================

  function initAccreditationsAnimations() {
    initLogoSliderSection({
      sectionId: "accreditations",
      sliderTrackSelector: ".accred-slider-track",
      sliderWrapperSelector: ".accred-slider-wrapper",
      cardSelector: ".accred-slider-track .accred-card",
      fades: [
        { selector: "#accreditations .section-label", y: 20, duration: 0.6 },
        { selector: ".accreditations__heading", y: 30, duration: 0.8 },
        {
          selector: ".accreditations__subheading",
          y: 30,
          duration: 0.8,
          delay: 0.1,
        },
        {
          selector: ".accreditations__badges",
          y: 20,
          duration: 0.6,
          stagger: 0.1,
        },
        { selector: ".accreditations__trust", y: 20, duration: 0.6, delay: 0.3 },
      ],
    });
  }

  // =========================================================
  // ── Alumni Network Section (Slider Portion) ──────────────
  // Note: Heading/label text-reveal handled separately in
  // initAlumniAnimations(); this handles slider + remaining fades.
  // =========================================================

  function initNetworkAnimations() {
    initLogoSliderSection({
      sectionId: "alumni-network",
      sliderTrackSelector: ".network-slider-track",
      sliderWrapperSelector: ".network-slider-wrapper",
      cardSelector: ".network-slider-track .network-card",
      fades: [
        { selector: "#alumni-network .section-label", y: 20, duration: 0.6 },
        { selector: ".network__heading", y: 30, duration: 0.8 },
        {
          selector: ".network__subheading",
          y: 30,
          duration: 0.8,
          delay: 0.1,
        },
        {
          selector: ".network__description",
          y: 30,
          duration: 0.8,
          delay: 0.2,
        },
        { selector: ".network__trust", y: 20, duration: 0.6, delay: 0.3 },
      ],
    });
  }
  function initAllAnimations() {
    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (!prefersReducedMotion) {
      initHeroAnimations();
    } else {
      gsap.set(
        [
          ".text-reveal-inner",
          ".fade-up",
          ".hero__accent-bar",
          ".hero__video-fallback",
          ".hero__video",
          ".hero__overlay",
        ],
        { clearProps: "all" },
      );
      gsap.set([".text-reveal-inner", ".fade-up"], {
        opacity: 1,
        y: 0,
        clearProps: "transform",
      });
      if (elementExists(".hero__accent-bar")) {
        gsap.set(".hero__accent-bar", { opacity: 1, scaleY: 1 });
      }
    }

    initDataAnimations();

    initHeroScrollAnimations();

    initNumbersAnimations();

    initWIMAnimations();

    initWWAAnimations();
    
    initCEOAnimations();

    initWWDAnimations();

    initHWDIAnimations();

    initAlumniAnimations();

    initProgramsAnimations();

    initWhyMaverickAnimations();

    initOpportunitiesAnimations();

    initPartnersAnimations();

    initInsightsAnimations();

    initEventsAnimations();

    initTestimonialsAnimations();

    initFinalCTAAnimations();

    initFooterAnimations();

    initAccreditationsAnimations();

    initNetworkAnimations();

    if (typeof ScrollTrigger !== "undefined") {
      ScrollTrigger.refresh();
    }
  }

  function startAnimations() {
    if (window.__animationsStarted) return;
    window.__animationsStarted = true;
    initAllAnimations();
  }

  if (window.__lenisReady) {
    startAnimations();
  } else {
    document.addEventListener("lenisReady", startAnimations, { once: true });
  }

  setTimeout(function () {
    if (!window.__animationsStarted) {
      console.warn("lenisReady never fired â€” starting anyway");
      startAnimations();
    }
  }, 1000);
})();


