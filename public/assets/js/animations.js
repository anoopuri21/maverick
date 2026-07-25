(function () {
  "use strict";

  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    gsap.registerPlugin(ScrollTrigger);
  } else {
    console.warn("Animations: GSAP or ScrollTrigger not loaded.");
    return;
  }

  // =========================================================
  // UTILITY FUNCTIONS (GLOBALLY SHOWN / GENERAL PURPOSE)
  // =========================================================

  function elementExists(selector) {
    return document.querySelector(selector) !== null;
  }

  function isMobile() {
    return window.innerWidth < 768;
  }

  function prefersReducedMotion() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  const activeSliders = new Map();

  // =========================================================
  // GENERIC / REUSABLE ANIMATION FUNCTIONS
  // =========================================================

  /**
   * Reusable scroll reveal animation helper.
   * Finds .text-reveal-inner and .fade-up elements within a container
   * and triggers their GSAP animations with lightweight defaults for mobile.
   */
  function revealSection(sectionSelector, options = {}) {
    const section = document.querySelector(sectionSelector);
    if (!section) return;

    const {
      start = "top 75%",
      textDuration = 0.9,
      textStagger = 0.12,
      fadeDuration = 0.7,
      fadeStagger = 0.1,
    } = options;

    const textReveals = section.querySelectorAll(".text-reveal-inner");
    const fadeUps = section.querySelectorAll(".fade-up");

    if (prefersReducedMotion()) {
      if (textReveals.length) gsap.set(textReveals, { y: "0%", clearProps: "transform" });
      if (fadeUps.length) gsap.set(fadeUps, { opacity: 1, y: 0, clearProps: "transform" });
      return;
    }

    if (textReveals.length) {
      // Set initial state
      gsap.set(textReveals, { y: "110%" });

      gsap.to(textReveals, {
        y: "0%",
        duration: textDuration,
        stagger: textStagger,
        ease: "power3.out",
        scrollTrigger: {
          trigger: section,
          start: start,
          toggleActions: "play none none none",
        },
      });
    }

    if (fadeUps.length) {
      // Lightweight Y displacement on mobile (16px to 24px instead of 40px)
      const initialY = isMobile() ? 16 : 40;
      gsap.set(fadeUps, { opacity: 0, y: initialY });

      gsap.to(fadeUps, {
        opacity: 1,
        y: 0,
        duration: isMobile() ? fadeDuration * 0.8 : fadeDuration,
        stagger: isMobile() ? fadeStagger * 0.7 : fadeStagger,
        ease: "power2.out",
        scrollTrigger: {
          trigger: section,
          start: start,
          toggleActions: "play none none none",
        },
      });
    }
  }

  /**
   * Reusable infinite horizontal marquee/slider.
   * Clones children to create seamless wrapping loops and pauses on hover.
   */
  function initInfiniteSlider(trackSelector, wrapperSelector, options = {}) {
    const {
      duration = 50,
      direction = "left",
      enableOnMobile = true,
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
      return null;
    }

    const cards = sliderTrack.children;
    if (cards.length === 0) {
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
      },
    });

    const targetX = direction === "left" ? -totalWidth : totalWidth;
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
        { element: sliderWrapper, event: "mouseenter", handler: handleMouseEnter },
        { element: sliderWrapper, event: "mouseleave", handler: handleMouseLeave },
      ],
      cleanup: function () {
        this.timeline.kill();
        this.eventListeners.forEach(({ element, event, handler }) => {
          element.removeEventListener(event, handler);
        });

        const allCards = Array.from(this.track.children);
        const cardsToRemove = allCards.slice(originalCards.length);
        cardsToRemove.forEach((card) => card.remove());

        this.track.style.willChange = "";
        gsap.set(this.track, { x: 0, clearProps: "transform" });

        activeSliders.delete(trackSelector);
      },
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

  window.addEventListener("beforeunload", cleanupAllAnimations);

  /**
   * Reusable numerical counter animation helper.
   */
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

  /**
   * Reusable logo slider sections (Accreditations & Alumni Network).
   */
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

    if (prefersReducedMotion()) {
      gsap.set(allAnimatedSelectors, { opacity: 1, y: 0, x: 0, scale: 1 });
      return;
    }

    // Infinite marquee
    initInfiniteSlider(sliderTrackSelector, sliderWrapperSelector, {
      duration: 50,
      direction: "left",
      enableOnMobile: true,
    });

    // Mobile check - lighter experience (skip scale triggers, set final position)
    if (isMobile()) {
      gsap.set(allAnimatedSelectors, { opacity: 1, y: 0, x: 0, scale: 1 });
      return;
    }

    // Scroll reveal fade-ups
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

    // Scale-in slider cards
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
  // PAGE/SECTION-SPECIFIC MODULE IMPLEMENTATIONS
  // =========================================================

  // --- HERO ENTRANCE (HOMEPAGE) ---
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
      "[data-hero-sub], [data-hero-ctas], [data-hero-trust]"
    );
    if (fadeUps.length) {
      gsap.set(fadeUps, { opacity: 0, y: isMobile() ? 15 : 30 });
    }

    const heroTl = gsap.timeline({ delay: 0.3 });

    if (elementExists(".hero__video")) {
      heroTl.fromTo(
        ".hero__video",
        { opacity: 0 },
        { opacity: 1, duration: 1.5, ease: "power2.out", overwrite: true },
        0
      );
    }

    if (elementExists(".hero__overlay")) {
      heroTl.fromTo(
        ".hero__overlay",
        { opacity: 0 },
        { opacity: 1, duration: 1.0, ease: "power2.inOut", overwrite: true },
        "<"
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
        0.6
      );
    }

    if (elementExists(".hero__eyebrow-line")) {
      heroTl.fromTo(
        ".hero__eyebrow-line",
        { width: "0px" },
        { width: "40px", duration: 0.6, ease: "power2.out", overwrite: true },
        0.8
      );
    }

    if (elementExists(".hero__eyebrow .text-reveal-inner")) {
      heroTl.fromTo(
        ".hero__eyebrow .text-reveal-inner",
        { y: "110%" },
        { y: "0%", duration: 0.7, ease: "power3.out", overwrite: true },
        1.0
      );
    }

    if (heroWords.length) {
      heroTl.fromTo(
        heroWords,
        { y: "110%" },
        {
          y: "0%",
          duration: 0.9,
          stagger: isMobile() ? 0.08 : 0.12,
          ease: "power3.out",
          overwrite: true,
        },
        1.2
      );
    }

    if (elementExists("[data-hero-sub]")) {
      heroTl.fromTo(
        "[data-hero-sub]",
        { opacity: 0, y: isMobile() ? 15 : 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: "power2.out",
          overwrite: true,
        },
        1.7
      );
    }

    if (elementExists("[data-hero-ctas]")) {
      heroTl.fromTo(
        "[data-hero-ctas]",
        { opacity: 0, y: isMobile() ? 12 : 24 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: "power2.out",
          overwrite: true,
        },
        1.9
      );
    }

    if (elementExists("[data-hero-trust]")) {
      heroTl.fromTo(
        "[data-hero-trust]",
        { opacity: 0, y: isMobile() ? 10 : 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          overwrite: true,
        },
        2.1
      );
    }

    return heroTl;
  }

  // --- HERO ENTRANCE (OUR STORY PAGE) ---
  function initOurStoryHeroAnimations() {
    const hero = document.querySelector(".story-hero");
    if (!hero) return;

    const textReveals = hero.querySelectorAll(".text-reveal-inner");
    const fadeUps = hero.querySelectorAll(".fade-up");

    if (prefersReducedMotion()) {
      if (textReveals.length) gsap.set(textReveals, { y: "0%", clearProps: "transform" });
      if (fadeUps.length) gsap.set(fadeUps, { opacity: 1, y: 0, clearProps: "transform" });
      return;
    }

    if (textReveals.length) gsap.set(textReveals, { y: "110%" });
    if (fadeUps.length) gsap.set(fadeUps, { opacity: 0, y: isMobile() ? 15 : 30 });

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
        0.2
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
        0.5
      );
    }

    // Parallax on hero content
    if (elementExists(".story-hero__content")) {
      gsap.to(".story-hero__content", {
        y: isMobile() ? -20 : -60,
        ease: "none",
        scrollTrigger: {
          trigger: ".story-hero",
          start: "top top",
          end: "bottom top",
          scrub: 1.5,
        },
      });
    }

    return heroTl;
  }

  // --- HERO SCROLL-PARALLAX ANIMATIONS (HOMEPAGE) ---
  function initHeroScrollAnimations() {
    const hero = document.querySelector("#hero");
    if (!hero) return;

    let contentY = isMobile() ? -30 : -80;
    let videoScale = 1.08;
    let grainY = -30;

    if (prefersReducedMotion()) {
      contentY *= 0.5;
      videoScale = 1.03;
      grainY *= 0.5;
    }
    if (isMobile()) {
      grainY = 0;
    }

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

    if (elementExists(".hero__video-fallback") || elementExists(".hero__video")) {
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

  // --- NUMBERS / IMPACT SECTION ANIMATIONS ---
  function initNumbersAnimations() {
    const section = document.querySelector("#numbers") || document.querySelector("#impact");
    if (!section) return;

    const sectionId = section.id;

    if (prefersReducedMotion()) {
      gsap.set(
        section.querySelectorAll(
          ".text-reveal-inner, .fade-up, .numbers__section-divider-line, .numbers__card-line, .numbers__header-divider"
        ),
        { clearProps: "all", opacity: 1 }
      );
      gsap.set(section.querySelectorAll(".text-reveal-inner"), { y: "0%" });

      const cards = section.querySelectorAll("[data-counter-target]");
      cards.forEach((card) => {
        const target = parseInt(card.getAttribute("data-counter-target"), 10);
        const counterEl = card.querySelector("[data-counter]");
        if (!isNaN(target) && counterEl) {
          counterEl.textContent = target.toLocaleString("en-US");
        }
      });
      return;
    }

    // Desktop/Mobile Responsive Scroll Trigger Logic
    ScrollTrigger.matchMedia({
      // Desktop Setup
      "(min-width: 769px)": function () {
        revealSection("#" + sectionId);

        const dividerLine = section.querySelector(".numbers__section-divider-line");
        if (dividerLine) {
          gsap.fromTo(
            dividerLine,
            { scaleX: 0, transformOrigin: "left center" },
            {
              scaleX: 1,
              duration: 1.2,
              ease: "power2.inOut",
              scrollTrigger: {
                trigger: section.querySelector(".numbers__section-divider") || section,
                start: "top 85%",
                toggleActions: "play none none none",
              },
            }
          );
        }

        const cards = section.querySelectorAll(".numbers__card, .impact__stat");
        if (cards.length) {
          const cardLines = section.querySelectorAll(".numbers__card-line");
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
                  trigger: section.querySelector(".numbers__grid") || section,
                  start: "top 75%",
                  toggleActions: "play none none none",
                },
              }
            );
          }
        }

        const headerDivider = section.querySelector(".numbers__header-divider");
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
                trigger: section.querySelector(".numbers__header") || section,
                start: "top 75%",
                toggleActions: "play none none none",
              },
            }
          );
        }
      },

      // Mobile Setup (Lighter lines scaling)
      "(max-width: 768px)": function () {
        revealSection("#" + sectionId);

        const dividerLine = section.querySelector(".numbers__section-divider-line");
        if (dividerLine) {
          gsap.fromTo(
            dividerLine,
            { scaleX: 0, transformOrigin: "left center" },
            {
              scaleX: 1,
              duration: 1.0,
              ease: "power2.inOut",
              scrollTrigger: {
                trigger: section.querySelector(".numbers__section-divider") || section,
                start: "top 85%",
                toggleActions: "play none none none",
              },
            }
          );
        }

        const cards = section.querySelectorAll(".numbers__card, .impact__stat");
        if (cards.length) {
          const cardLines = section.querySelectorAll(".numbers__card-line");
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
                  trigger: section.querySelector(".numbers__grid") || section,
                  start: "top 75%",
                  toggleActions: "play none none none",
                },
              }
            );
          }
        }
      },
    });

    // Run Counter animation triggers
    const counterCards = section.querySelectorAll("[data-counter-target]");
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
  }

  // --- WHAT IS MAVERICK PINNING ANIMATIONS (CINEMATIC) ---
  function initWIMAnimations() {
    const section = document.querySelector("#what-is-maverick");
    if (!section) return;

    const pinWrapper = section.querySelector(".wim__pin-wrapper");
    const headingWrapper = section.querySelector(".wim__heading-wrapper");
    const label = section.querySelector(".wim__label");
    const bgImage = section.querySelector(".wim__bg-image");
    const finalEl = section.querySelector(".wim__final");
    const statements = gsap.utils.toArray(section.querySelectorAll(".wim__statement"));
    const totalStatements = statements.length;

    if (!pinWrapper || !headingWrapper || totalStatements === 0) {
      return;
    }

    ScrollTrigger.getAll().forEach((st) => {
      if (st.trigger === section) st.kill();
    });

    if (prefersReducedMotion()) {
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

    // Lightweight responsive settings
    const cfg = isMobile()
      ? {
          headingInitY: "-5vh",
          headingSettleY: "-1vh",
          entryStart: "top 70%",
          entryEnd: "top 20%",
          pinEnd: "+=80%",
          bgScale: 1.05,
          labelOpacity: 1,
          scrubEntry: 0.5,
          scrubPin: 0.8,
        }
      : {
          headingInitY: "-15vh",
          headingSettleY: "-3vh",
          entryStart: "top 65%",
          entryEnd: "top 15%",
          pinEnd: "+=140%",
          bgScale: 1.12,
          labelOpacity: 0.9,
          scrubEntry: 1,
          scrubPin: 1.2,
        };

    const STMT_START = 0.05;
    const STMT_ZONE_END = 0.62;
    const FINAL_POS = 0.66;
    const FADE_START = 0.88;
    const FADE_DUR = 0.12;

    const stmtZone = STMT_ZONE_END - STMT_START;
    const STMT_GAP = totalStatements > 1 ? stmtZone / (totalStatements - 1) : 0;

    gsap.set(headingWrapper, { y: cfg.headingInitY, opacity: 0 });
    gsap.set(label, { y: 10, opacity: 0 });
    gsap.set(statements, { opacity: 0 });
    gsap.set(finalEl, { y: 12, opacity: 0 });
    gsap.set(pinWrapper, { opacity: 1 });

    statements.forEach((stmt) => {
      const txt = stmt.querySelector(".wim__statement-text");
      if (txt) gsap.set(txt, { y: "100%" });
    });

    if (bgImage) gsap.set(bgImage, { scale: 1 });

    const entryTl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: cfg.entryStart,
        end: cfg.entryEnd,
        scrub: cfg.scrubEntry,
      },
    });

    entryTl
      .to(headingWrapper, {
        y: "0vh",
        opacity: 1,
        duration: 1,
        ease: "power2.out",
      })
      .to(
        label,
        {
          y: 0,
          opacity: cfg.labelOpacity,
          duration: 0.7,
          ease: "power2.out",
        },
        "-=0.5"
      );

    const wimTl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: "top top",
        end: cfg.pinEnd,
        scrub: cfg.scrubPin,
        pin: pinWrapper,
        anticipatePin: 1,
        pinSpacing: true,
        invalidateOnRefresh: true,
      },
    });

    if (bgImage) {
      wimTl.to(
        bgImage,
        {
          scale: cfg.bgScale,
          duration: 1,
          ease: "none",
        },
        0
      );
    }

    wimTl.to(
      headingWrapper,
      {
        y: cfg.headingSettleY,
        duration: 0.3,
        ease: "power1.inOut",
      },
      0
    );

    statements.forEach((stmt, index) => {
      const stmtText = stmt.querySelector(".wim__statement-text");
      const pos = STMT_START + index * STMT_GAP;

      wimTl.to(
        stmt,
        {
          opacity: 1,
          duration: 0.15,
          ease: "power2.out",
        },
        pos
      );

      if (stmtText) {
        wimTl.to(
          stmtText,
          {
            y: "0%",
            duration: 0.18,
            ease: "power3.out",
          },
          pos
        );
      }
    });

    wimTl.to(
      finalEl,
      {
        opacity: 1,
        y: 0,
        duration: 0.08,
        ease: "power2.out",
      },
      FINAL_POS
    );

    wimTl.to(
      pinWrapper,
      {
        opacity: 0,
        duration: FADE_DUR,
        ease: "power1.inOut",
      },
      FADE_START
    );
  }

  // --- WHO WE ARE ANIMATIONS ---
  function initWWAAnimations() {
    if (!elementExists("#who-we-are")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          ".wwa__heading-line .text-reveal-inner",
          ".wwa__body",
          ".wwa__stats",
          ".wwa__cta",
          ".wwa__image-accent",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set(".wwa__heading-line .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#who-we-are");

    const image = document.querySelector(".wwa__image");
    if (image) {
      gsap.to(image, {
        y: isMobile() ? -15 : -40,
        ease: "none",
        scrollTrigger: {
          trigger: ".wwa__image-col",
          start: "top bottom",
          end: "bottom top",
          scrub: isMobile() ? 1.0 : 1.5,
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
        }
      );
    }
  }

  // --- CEO MESSAGE (SHARED SECTION) ---
  function initCEOAnimations() {
    if (!elementExists("#ceo-message")) return;

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
        }
      );
    }

    const headingLines = document.querySelectorAll("#ceo-message .text-reveal-inner");
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
        toggleActions: "play none none none",
      },
    });

    tl.fromTo(
      ".ceo__image-wrapper",
      { opacity: 0, scale: 0.95 },
      { opacity: 1, scale: 1, duration: 0.9, ease: "power2.out" }
    );

    tl.fromTo(
      [".section-label", ".ceo__quote", ".ceo__body", ".ceo__signature"],
      { opacity: 0, y: isMobile() ? 20 : 40 },
      {
        opacity: 1,
        y: 0,
        stagger: 0.15,
        duration: 0.8,
        ease: "power2.out",
      },
      "-=0.4"
    );
  }

  // --- WHAT WE DO SECTION ---
  function initWWDAnimations() {
    if (!elementExists("#what-we-do")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          ".wwd__heading-line .text-reveal-inner",
          ".wwd__context",
          ".wwd__card",
          ".wwd__card-index",
          ".wwd__card-item",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set(".wwd__heading-line .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#what-we-do");

    const cards = document.querySelectorAll(".wwd__card");
    if (cards.length) {
      const cardIndexes = document.querySelectorAll(".wwd__card-index");
      if (cardIndexes.length) {
        gsap.fromTo(
          cardIndexes,
          { opacity: 0 },
          {
            opacity: 1,
            duration: 0.5,
            stagger: isMobile() ? 0.08 : 0.15,
            delay: 0.2,
            ease: "power2.out",
            scrollTrigger: {
              trigger: ".wwd__grid",
              start: "top 75%",
              toggleActions: "play none none none",
            },
          }
        );
      }

      cards.forEach((card) => {
        const items = card.querySelectorAll(".wwd__card-item");
        if (items.length) {
          gsap.fromTo(
            items,
            { opacity: 0, x: isMobile() ? -5 : -10 },
            {
              opacity: 1,
              x: 0,
              duration: 0.4,
              stagger: isMobile() ? 0.05 : 0.08,
              ease: "power2.out",
              scrollTrigger: {
                trigger: card,
                start: "top 70%",
                toggleActions: "play none none none",
              },
            }
          );
        }
      });
    }
  }

  // --- HOW WE DO IT SECTION ---
  function initHWDIAnimations() {
    if (!elementExists("#how-we-do-it")) return;

    gsap.set(".hwdi__heading-line .text-reveal-inner", { y: "110%" });
    gsap.set(".hwdi__subtitle", { opacity: 0 });
    gsap.set(".hwdi__step", { opacity: 0 });
    gsap.set(".hwdi__step-number", { scale: 0.7, opacity: 0 });
    gsap.set(".hwdi__step-accent", { scaleY: 0, transformOrigin: "top center" });
    gsap.set(".hwdi__connector-line", { scaleX: 0, transformOrigin: "left center" });

    if (prefersReducedMotion()) {
      gsap.set(
        [
          ".hwdi__heading-line .text-reveal-inner",
          ".hwdi__subtitle",
          ".hwdi__step",
          ".hwdi__step-number",
          ".hwdi__step-accent",
          ".hwdi__connector-line",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set(".hwdi__heading-line .text-reveal-inner", { y: "0%" });
      gsap.set(".hwdi__step-number", { scale: 1 });
      gsap.set(".hwdi__step-accent", { scaleY: 1 });
      gsap.set(".hwdi__connector-line", { scaleX: 1 });
      return;
    }

    revealSection("#how-we-do-it");

    const steps = document.querySelectorAll(".hwdi__step");
    if (steps.length) {
      const stepNumbers = document.querySelectorAll(".hwdi__step-number");
      if (stepNumbers.length) {
        gsap.fromTo(
          stepNumbers,
          { scale: 0.8, opacity: 0 },
          {
            scale: 1,
            opacity: 1,
            duration: 0.5,
            stagger: isMobile() ? 0.12 : 0.2,
            ease: "back.out(1.5)",
            scrollTrigger: {
              trigger: ".hwdi__steps",
              start: "top 70%",
              toggleActions: "play none none none",
            },
          }
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
            stagger: isMobile() ? 0.12 : 0.2,
            ease: "power2.out",
            scrollTrigger: {
              trigger: ".hwdi__steps",
              start: "top 70%",
              toggleActions: "play none none none",
            },
          }
        );
      }
    }

    if (!isMobile()) {
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
          }
        );
      }
    }
  }

  // --- ALUMNI NETWORK TEXT PORTION ---
  function initAlumniAnimations() {
    if (!elementExists("#alumni-network")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          "#alumni-network .section-label",
          ".alumni__heading-line .text-reveal-inner",
          ".alumni__subtitle",
          ".alumni__marquee-wrapper",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set(".alumni__heading-line .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#alumni-network");

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
        }
      );
    }
  }

  // --- FEATURED PROGRAMS HORIZONTAL PANEL SLIDER (DESKTOP) ---
  function initProgramsAnimations() {
    if (!elementExists("#featured-programs")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          "#featured-programs .section-label",
          "#featured-programs .text-reveal-inner",
          "#featured-programs .programs__subtitle",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set("#featured-programs .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#featured-programs");

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

  // --- WHY MAVERICK SECTION ---
  function initWhyMaverickAnimations() {
    if (!elementExists("#why-maverick")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          "#why-maverick .section-label",
          "#why-maverick .text-reveal-inner",
          "#why-maverick .why__subtitle",
          "#why-maverick .why__tile",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set("#why-maverick .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#why-maverick");

    const tiles = document.querySelectorAll("#why-maverick .why__tile");
    if (tiles.length) {
      gsap.fromTo(
        tiles,
        { opacity: 0, y: isMobile() ? 20 : 40 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          stagger: isMobile() ? 0.06 : 0.1,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#why-maverick .why__grid",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        }
      );
    }
  }

  // --- GLOBAL OPPORTUNITIES SPLIT REVEALS ---
  function initOpportunitiesAnimations() {
    if (!elementExists("#global-opportunities")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          "#global-opportunities .section-label",
          "#global-opportunities .text-reveal-inner",
          "#global-opportunities .opportunities__subtitle",
          "#global-opportunities .opportunities__column-header",
          "#global-opportunities .opportunities__item",
          "#global-opportunities .opportunities__divider",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set("#global-opportunities .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#global-opportunities");

    const divider = document.querySelector("#global-opportunities .opportunities__divider");
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

    const columnHeaders = document.querySelectorAll("#global-opportunities .opportunities__column-header");
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
      "#global-opportunities .opportunities__column--left .opportunities__item"
    );
    if (leftItems.length) {
      gsap.to(leftItems, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: isMobile() ? 0.05 : 0.08,
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
      "#global-opportunities .opportunities__column--right .opportunities__item"
    );
    if (rightItems.length) {
      gsap.to(rightItems, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: isMobile() ? 0.05 : 0.08,
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

  // --- UNIVERSITY PARTNERS REVEAL ---
  function initPartnersAnimations() {
    if (!elementExists("#university-partners")) return;

    if (prefersReducedMotion()) {
      gsap.set(
        [
          "#university-partners .section-label",
          "#university-partners .text-reveal-inner",
          "#university-partners .partners__pin",
          "#university-partners .partners__detail-panel",
          "#university-partners .partners__mobile-item",
        ],
        { clearProps: "all", opacity: 1 }
      );
      gsap.set("#university-partners .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#university-partners");

    setTimeout(() => {
      const mobileItems = document.querySelectorAll("#university-partners .partners__mobile-item");
      if (mobileItems.length) {
        gsap.set(mobileItems, { opacity: 0, x: isMobile() ? -10 : -20 });
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

    const detailPanel = document.querySelector("#university-partners .partners__detail-panel");
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

  // --- FACULTY INSIGHTS REVEAL ---
  function initInsightsAnimations() {
    if (!elementExists("#faculty-insights")) return;

    if (prefersReducedMotion()) {
      gsap.set(["#faculty-insights .text-reveal-inner", "#faculty-insights .fade-up"], {
        clearProps: "all",
        opacity: 1,
      });
      gsap.set("#faculty-insights .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#faculty-insights");

    const cards = document.querySelectorAll("#faculty-insights .insights__card");
    if (cards.length) {
      gsap.fromTo(
        cards,
        { opacity: 0, x: isMobile() ? 15 : 40 },
        {
          opacity: 1,
          x: 0,
          duration: 0.7,
          stagger: isMobile() ? 0.08 : 0.12,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#faculty-insights .insights__scroll",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        }
      );
    }
  }

  // --- UPCOMING EVENTS REVEAL ---
  function initEventsAnimations() {
    if (!elementExists("#upcoming-events")) return;

    if (prefersReducedMotion()) {
      gsap.set(["#upcoming-events .text-reveal-inner", "#upcoming-events .fade-up"], {
        clearProps: "all",
        opacity: 1,
      });
      gsap.set("#upcoming-events .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#upcoming-events");

    const cards = document.querySelectorAll("#upcoming-events .events__card");
    if (cards.length) {
      gsap.fromTo(
        cards,
        { opacity: 0, x: isMobile() ? 15 : 40 },
        {
          opacity: 1,
          x: 0,
          duration: 0.6,
          stagger: isMobile() ? 0.06 : 0.1,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#upcoming-events .events__scroll",
            start: "top 80%",
            toggleActions: "play none none none",
          },
        }
      );
    }
  }

  // --- VIDEO TESTIMONIALS REVEAL ---
  function initTestimonialsAnimations() {
    if (!elementExists("#video-testimonials")) return;

    if (prefersReducedMotion()) {
      gsap.set(["#video-testimonials .text-reveal-inner", "#video-testimonials .fade-up"], {
        clearProps: "all",
        opacity: 1,
      });
      gsap.set("#video-testimonials .text-reveal-inner", { y: "0%" });
      return;
    }

    revealSection("#video-testimonials");

    const cards = document.querySelectorAll("#video-testimonials .testimonials__card");
    if (cards.length) {
      gsap.fromTo(
        cards,
        { opacity: 0, x: isMobile() ? 15 : 40 },
        {
          opacity: 1,
          x: 0,
          duration: 0.6,
          stagger: isMobile() ? 0.05 : 0.08,
          ease: "power2.out",
          scrollTrigger: {
            trigger: "#video-testimonials .testimonials__scroll",
            start: "top 75%",
            toggleActions: "play none none none",
          },
        }
      );
    }
  }

  // --- FINAL CTA SECTION ---
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

    revealSection("#final-cta", { start: "top 70%" });

    const buttons = document.querySelectorAll("#final-cta .final-cta__btn");
    if (buttons.length) {
      gsap.fromTo(
        buttons,
        { opacity: 0, y: isMobile() ? 10 : 20 },
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
        }
      );
    }

    const phone = document.querySelector("#final-cta .final-cta__phone");
    if (phone) {
      gsap.fromTo(
        phone,
        { opacity: 0, y: isMobile() ? 8 : 15 },
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
        }
      );
    }
  }

  // --- FOOTER SECTION ---
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

    if (prefersReducedMotion()) return;

    const cols = document.querySelectorAll(".footer__col");
    const bottom = document.querySelector(".footer__bottom");

    if (cols.length) gsap.set(cols, { opacity: 0, y: isMobile() ? 15 : 30 });
    if (bottom) gsap.set(bottom, { opacity: 0, y: isMobile() ? 10 : 20 });

    if (cols.length) {
      gsap.to(cols, {
        opacity: 1,
        y: 0,
        duration: 0.7,
        stagger: isMobile() ? 0.08 : 0.12,
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

  // --- ACCREDITATIONS & PARTNERSHIPS SECTION ---
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

  // --- ALUMNI NETWORK SECTION (MARQUEE PORTION) ---
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

  // =========================================================
  // BOOTSTRAPPER AND INITIALIZE
  // =========================================================

  function initAllAnimations() {
    const isOurStoryPage = window.location.pathname.includes("our-story");

    if (!prefersReducedMotion()) {
      if (isOurStoryPage) {
        initOurStoryHeroAnimations();
      } else {
        initHeroAnimations();
      }
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
        { clearProps: "all" }
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

    if (!isOurStoryPage) {
      initHeroScrollAnimations();
    }

    // Generic reveal animations for simple sections on Our Story page
    if (isOurStoryPage) {
      revealSection("#beginning");
      revealSection("#today");
      revealSection("#journey");
      revealSection("#vision");
      revealSection("#awards");
    }

    // Initialize all common or page-specific module scripts dynamically
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
      console.warn("lenisReady never fired – starting animations fallback");
      startAnimations();
    }
  }, 800);

  // Journeys/Timeline Scroll Reveal (from our-story page setup)
  document.addEventListener("DOMContentLoaded", function () {
    const timelineItems = document.querySelectorAll(".journey__item");

    if (!timelineItems.length) return;

    const observerOptions = {
      threshold: 0.2,
      rootMargin: "0px 0px -100px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    timelineItems.forEach((item) => observer.observe(item));
  });
})();
