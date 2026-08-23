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

  function isMobile() {
    return window.innerWidth < 768;
  }

  const activeSliders = new Map();

  function initInfiniteSlider(trackSelector, wrapperSelector, options = {}) {
    const {
      duration = 50,
      direction = "left",
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
      console.warn(
        `Slider elements not found: ${trackSelector}, ${wrapperSelector}`,
      );
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

    sliderWrapper.addEventListener("mouseenter", handleMouseEnter, {
      passive: true,
    });
    sliderWrapper.addEventListener("mouseleave", handleMouseLeave, {
      passive: true,
    });

    const sliderInstance = {
      timeline: sliderTl,
      track: sliderTrack,
      wrapper: sliderWrapper,
      eventListeners: [
        {
          element: sliderWrapper,
          event: "mouseenter",
          handler: handleMouseEnter,
        },
        {
          element: sliderWrapper,
          event: "mouseleave",
          handler: handleMouseLeave,
        },
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

    if (AnimationUtils.prefersReducedMotion) {
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

    // Shared animations (same for desktop and mobile)
    AnimationUtils.textReveal(
      ".numbers__heading-line .text-reveal-inner",
      { trigger: "#numbers" },
    );
    AnimationUtils.sectionLabel("#numbers");
    AnimationUtils.lineScale(".numbers__section-divider-line", {
      trigger: ".numbers__section-divider",
      start: "top 85%",
      duration: 1.2,
    });
    AnimationUtils.fadeUp(".numbers__context", {
      trigger: ".numbers__context",
      y: AnimationUtils.responsive(30, 20),
    });

    // Responsive card animations
    const cfg = AnimationUtils.responsive(
      { y: 40, stagger: 0.1, lineStagger: 0.1, lineDelay: 0.3 },
      { y: 20, stagger: 0.06, lineStagger: 0.06, lineDelay: 0.2 },
    );

    AnimationUtils.cards(".numbers__card", {
      trigger: ".numbers__grid",
      start: "top 80%",
      y: cfg.y,
      stagger: cfg.stagger,
    });

    AnimationUtils.lineScale(".numbers__card-line", {
      trigger: ".numbers__grid",
      start: "top 75%",
      stagger: cfg.lineStagger,
      delay: cfg.lineDelay,
    });

    // Counter animation (unique logic)
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

    // Header divider
    AnimationUtils.lineScale(".numbers__header-divider", {
      trigger: ".numbers__header",
      start: "top 75%",
      delay: 0.4,
      duration: 1.0,
      origin: "top center",
    });
  }

  // =========================================================
  // What Is Maverick Section Animations
  // =========================================================

  function initWIMAnimations() {
    const section = document.querySelector("#what-is-maverick");
    if (!section) return;

    const pinWrapper = section.querySelector(".wim__pin-wrapper");
    const headingWrapper = section.querySelector(".wim__heading-wrapper");
    const label = section.querySelector(".wim__label");
    const bgImage = section.querySelector(".wim__bg-image");
    const finalEl = section.querySelector(".wim__final");
    const statements = gsap.utils.toArray(
      section.querySelectorAll(".wim__statement"),
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
      "(prefers-reduced-motion: reduce)",
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
          headingInitY: "-8vh",
          headingSettleY: "-2vh",
          entryStart: "top 70%",
          entryEnd: "top 20%",
          pinEnd: "+=110%",
          bgScale: 1.08,
          labelOpacity: 1,
          scrubEntry: 0.8,
          scrubPin: 1,
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
        "-=0.5",
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
        0,
      );
    }

    wimTl.to(
      headingWrapper,
      {
        y: cfg.headingSettleY,
        duration: 0.3,
        ease: "power1.inOut",
      },
      0,
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
        pos,
      );

      if (stmtText) {
        wimTl.to(
          stmtText,
          {
            y: "0%",
            duration: 0.18,
            ease: "power3.out",
          },
          pos,
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
      FINAL_POS,
    );

    wimTl.to(
      pinWrapper,
      {
        opacity: 0,
        duration: FADE_DUR,
        ease: "power1.inOut",
      },
      FADE_START,
    );
  }

  // =========================================================
  // Who We Are Section Animations
  // =========================================================

  function initWWAAnimations() {
    if (!elementExists("#who-we-are")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        ".wwa__heading-line .text-reveal-inner",
        ".wwa__body",
        ".wwa__stats",
        ".wwa__cta",
        ".wwa__image-accent",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#who-we-are");
    AnimationUtils.textReveal(".wwa__heading-line .text-reveal-inner", {
      trigger: "#who-we-are",
    });
    AnimationUtils.fadeUp(".wwa__body", {
      trigger: ".wwa__body",
      y: AnimationUtils.responsive(30, 20),
    });
    AnimationUtils.fadeUp(".wwa__stats", {
      trigger: ".wwa__stats",
      y: AnimationUtils.responsive(30, 20),
    });
    AnimationUtils.fadeUp(".wwa__cta", {
      trigger: ".wwa__cta",
      y: AnimationUtils.responsive(24, 20),
      start: "top 85%",
    });

    // Image parallax (unique)
    AnimationUtils.parallax(".wwa__image", {
      trigger: ".wwa__image-col",
      y: AnimationUtils.responsive(-40, -20),
      scrub: AnimationUtils.responsive(1.5, 1),
    });

    // Image accent fade
    AnimationUtils.fadeUp(".wwa__image-accent", {
      trigger: ".wwa__image-wrapper",
      start: "top 70%",
      duration: 0.6,
    });
  }



  // =========================================================
  // What We Do Section Animations
  // =========================================================

  function initWWDAnimations() {
    if (!elementExists("#what-we-do")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        ".wwd__heading-line .text-reveal-inner",
        ".wwd__context",
        ".wwd__card",
        ".wwd__card-index",
        ".wwd__card-item",
      ]);
      return;
    }

    const cfg = AnimationUtils.responsive(
      { contextY: 30, cardY: 40, cardStagger: 0.15, indexDelay: 0.3, itemX: -10, itemStagger: 0.08 },
      { contextY: 20, cardY: 20, cardStagger: 0.1, indexDelay: 0.2, itemX: -8, itemStagger: 0.06 },
    );

    AnimationUtils.sectionLabel("#what-we-do");
    AnimationUtils.textReveal(".wwd__heading-line .text-reveal-inner", {
      trigger: "#what-we-do",
    });
    AnimationUtils.fadeUp(".wwd__context", {
      trigger: ".wwd__context",
      y: cfg.contextY,
    });
    AnimationUtils.cards(".wwd__card", {
      trigger: ".wwd__grid",
      start: "top 75%",
      y: cfg.cardY,
      stagger: cfg.cardStagger,
    });

    // Card indexes (unique)
    AnimationUtils.fadeUp(".wwd__card-index", {
      trigger: ".wwd__grid",
      start: "top 75%",
      y: 0,
      duration: 0.5,
      stagger: cfg.cardStagger,
      delay: cfg.indexDelay,
    });

    // Card items (unique - slide from left)
    document.querySelectorAll(".wwd__card").forEach((card) => {
      const items = card.querySelectorAll(".wwd__card-item");
      if (items.length) {
        gsap.fromTo(
          items,
          { opacity: 0, x: cfg.itemX },
          {
            opacity: 1,
            x: 0,
            duration: 0.4,
            stagger: cfg.itemStagger,
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

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        ".hwdi__heading-line .text-reveal-inner",
        ".hwdi__subtitle",
        ".hwdi__step",
        ".hwdi__step-number",
        ".hwdi__step-accent",
        ".hwdi__connector-line",
      ]);
      gsap.set(".hwdi__step-number", { scale: 1 });
      gsap.set(".hwdi__step-accent", { scaleY: 1 });
      gsap.set(".hwdi__connector-line", { scaleX: 1 });
      return;
    }

    const cfg = AnimationUtils.responsive(
      { subtitleY: 20, stepY: 40, stepStagger: 0.2, numScale: 0.8, numEase: "back.out(1.7)", accentStagger: 0.2, accentDur: 0.5, connectorStagger: 0.15 },
      { subtitleY: 16, stepY: 24, stepStagger: 0.15, numScale: 0.8, numEase: "back.out(1.5)", accentStagger: 0.15, accentDur: 0.4, connectorStagger: 0.15 },
    );

    AnimationUtils.sectionLabel("#how-we-do-it");
    AnimationUtils.textReveal(".hwdi__heading-line .text-reveal-inner", {
      trigger: "#how-we-do-it",
    });
    AnimationUtils.fadeUp(".hwdi__subtitle", {
      trigger: ".hwdi__subtitle",
      y: cfg.subtitleY,
    });
    AnimationUtils.cards(".hwdi__step", {
      trigger: ".hwdi__steps",
      start: "top 70%",
      y: cfg.stepY,
      stagger: cfg.stepStagger,
    });

    // Step numbers (unique - scale in with bounce)
    AnimationUtils.scaleIn(".hwdi__step-number", {
      trigger: ".hwdi__steps",
      start: "top 70%",
      scale: cfg.numScale,
      stagger: cfg.stepStagger,
    });

    // Step accents (unique - vertical scale)
    gsap.fromTo(
      ".hwdi__step-accent",
      { scaleY: 0 },
      {
        scaleY: 1,
        duration: cfg.accentDur,
        stagger: cfg.accentStagger,
        ease: "power2.out",
        scrollTrigger: {
          trigger: ".hwdi__steps",
          start: "top 70%",
          toggleActions: "play none none none",
        },
      },
    );

    // Connector lines (unique - horizontal scale)
    AnimationUtils.lineScale(".hwdi__connector-line", {
      trigger: ".hwdi__steps",
      start: "top 65%",
      stagger: cfg.connectorStagger,
    });
  }

  // =========================================================
  // Alumni Network Section Animations
  // =========================================================

  function initAlumniAnimations() {
    if (!elementExists("#alumni-network")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#alumni-network .section-label",
        ".alumni__heading-line .text-reveal-inner",
        ".alumni__subtitle",
        ".alumni__marquee-wrapper",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#alumni-network");
    AnimationUtils.textReveal("#alumni-network .text-reveal-inner", {
      trigger: "#alumni-network",
    });
    AnimationUtils.fadeUp(".alumni__subtitle", {
      trigger: ".alumni__subtitle",
      y: 20,
    });
    AnimationUtils.scaleIn(".alumni__marquee-wrapper", {
      trigger: ".alumni__marquee-wrapper",
      start: "top 85%",
      scale: 0.97,
    });
  }

  // =========================================================
  // Featured Programs Section Animations
  // =========================================================

  function initProgramsAnimations() {
    if (!elementExists("#featured-programs")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#featured-programs .section-label",
        "#featured-programs .text-reveal-inner",
        "#featured-programs .programs__subtitle",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#featured-programs");
    AnimationUtils.textReveal("#featured-programs .text-reveal-inner", {
      trigger: "#featured-programs",
      stagger: 0.1,
    });
    AnimationUtils.fadeUp("#featured-programs .programs__subtitle", {
      trigger: "#featured-programs .programs__subtitle",
      y: 20,
    });

    // Horizontal scroll (unique - desktop only)
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
  // CEO Section Animations
  // =========================================================

  function initCEOAnimations() {
    if (!elementExists("#ceo-message")) return;

    AnimationUtils.fadeUp(".ceo__image", {
      trigger: ".ceo__image-col",
      start: "top 70%",
      duration: 0.6,
    });

    AnimationUtils.textReveal("#ceo-message .text-reveal-inner", {
      trigger: "#ceo-message",
      start: "top 70%",
    });

    // CEO content timeline (unique)
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
      [
        "#ceo-message .section-label",
        "#ceo-message .ceo__quote",
        "#ceo-message .ceo__body",
        "#ceo-message .ceo__signature",
      ],
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
  // Why Maverick Section Animations
  // =========================================================

  function initWhyMaverickAnimations() {
    if (!elementExists("#why-maverick")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#why-maverick .section-label",
        "#why-maverick .text-reveal-inner",
        "#why-maverick .why__subtitle",
        "#why-maverick .why__tile",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#why-maverick");
    AnimationUtils.textReveal("#why-maverick .text-reveal-inner", {
      trigger: "#why-maverick",
    });
    AnimationUtils.fadeUp("#why-maverick .why__subtitle", {
      trigger: "#why-maverick .why__subtitle",
      y: 20,
    });
    AnimationUtils.cards("#why-maverick .why__tile", {
      trigger: "#why-maverick .why__grid",
      start: "top 80%",
      y: 40,
    });
  }

  function initGlobalAccessPointsAnimations() {
    if (!elementExists("#global-access-points")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#global-access-points .section-label",
        "#global-access-points .text-reveal-inner",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#global-access-points");
    AnimationUtils.textReveal("#global-access-points .text-reveal-inner", {
      trigger: "#global-access-points",
    });
  }

  // =========================================================
  // Global Opportunities & Pathways Section Animations
  // =========================================================

  function initOpportunitiesAnimations() {
    if (!elementExists("#global-opportunities")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#global-opportunities .section-label",
        "#global-opportunities .text-reveal-inner",
        "#global-opportunities .opportunities__subtitle",
        "#global-opportunities .opportunities__column-header",
        "#global-opportunities .opportunities__item",
        "#global-opportunities .opportunities__divider",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#global-opportunities");
    AnimationUtils.textReveal(
      "#global-opportunities .opportunities__heading .text-reveal-inner",
      { trigger: "#global-opportunities" },
    );
    AnimationUtils.fadeUp(
      "#global-opportunities .opportunities__subtitle",
      { trigger: "#global-opportunities", y: 20, delay: 0.2 },
    );
    AnimationUtils.lineScale(
      "#global-opportunities .opportunities__divider",
      { trigger: "#global-opportunities .opportunities__split", start: "top 80%" },
    );
    AnimationUtils.fadeUp(
      "#global-opportunities .opportunities__column-header",
      { trigger: "#global-opportunities .opportunities__split", start: "top 80%", stagger: 0.15 },
    );
    AnimationUtils.slideIn(
      "#global-opportunities .opportunities__column--left .opportunities__item",
      { trigger: "#global-opportunities .opportunities__split", start: "top 75%", x: 0, y: 24, duration: 0.5, stagger: 0.08, delay: 0.3 },
    );
    AnimationUtils.slideIn(
      "#global-opportunities .opportunities__column--right .opportunities__item",
      { trigger: "#global-opportunities .opportunities__split", start: "top 75%", x: 0, y: 24, duration: 0.5, stagger: 0.08, delay: 0.45 },
    );
  }

  // =========================================================
  // University Partners Section Animations
  // =========================================================

  function initPartnersAnimations() {
    if (!elementExists("#university-partners")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#university-partners .section-label",
        "#university-partners .text-reveal-inner",
        "#university-partners .partners__pin",
        "#university-partners .partners__detail-panel",
        "#university-partners .partners__mobile-item",
      ]);
      return;
    }

    // Mobile items with delay
    setTimeout(() => {
      AnimationUtils.slideIn(
        "#university-partners .partners__mobile-item",
        {
          trigger: "#university-partners .partners__mobile-list",
          start: "top 80%",
          x: -20,
          duration: 0.5,
          stagger: 0.05,
        },
      );
    }, 100);

    // Detail panel
    AnimationUtils.fadeUp("#university-partners .partners__detail-panel", {
      trigger: "#university-partners .partners__detail-panel",
      start: "top 85%",
      duration: 0.8,
    });
  }

  // =========================================================
  // Faculty Insights Section Animations
  // =========================================================

  function initInsightsAnimations() {
    if (!elementExists("#faculty-insights")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#faculty-insights .text-reveal-inner",
        "#faculty-insights .fade-up",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#faculty-insights");
    AnimationUtils.textReveal(
      "#faculty-insights .insights__heading-line .text-reveal-inner",
      { trigger: "#faculty-insights" },
    );
    AnimationUtils.fadeUp("#faculty-insights .insights__subtitle", {
      trigger: "#faculty-insights",
      y: 30,
    });
    AnimationUtils.slideIn("#faculty-insights .insights__card", {
      trigger: "#faculty-insights .insights__scroll",
      x: 40,
    });
  }

  // =========================================================
  // Upcoming Events Section Animations
  // =========================================================

  function initEventsAnimations() {
    if (!elementExists("#upcoming-events")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#upcoming-events .text-reveal-inner",
        "#upcoming-events .fade-up",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#upcoming-events");
    AnimationUtils.textReveal(
      "#upcoming-events .events__heading-line .text-reveal-inner",
      { trigger: "#upcoming-events" },
    );
    AnimationUtils.fadeUp("#upcoming-events .events__subtitle", {
      trigger: "#upcoming-events",
      y: 30,
    });
    AnimationUtils.slideIn("#upcoming-events .events__card", {
      trigger: "#upcoming-events .events__scroll",
      x: 40,
      duration: 0.6,
    });
  }

  // =========================================================
  // Video Testimonials Section Animations
  // =========================================================

  function initTestimonialsAnimations() {
    if (!elementExists("#video-testimonials")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#video-testimonials .text-reveal-inner",
        "#video-testimonials .fade-up",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#video-testimonials");
    AnimationUtils.textReveal(
      "#video-testimonials .testimonials__heading-line .text-reveal-inner",
      { trigger: "#video-testimonials" },
    );
    AnimationUtils.fadeUp("#video-testimonials .testimonials__subtitle", {
      trigger: "#video-testimonials",
      y: 30,
    });
    AnimationUtils.slideIn("#video-testimonials .testimonials__card", {
      trigger: "#video-testimonials .testimonials__scroll",
      x: 40,
      duration: 0.6,
      stagger: 0.08,
    });
  }

  // =========================================================
  // Final CTA Section Animations
  // =========================================================

  function initFinalCTAAnimations() {
    if (!elementExists("#final-cta")) return;

    if (AnimationUtils.prefersReducedMotion) {
      AnimationUtils.setReducedMotion([
        "#final-cta .text-reveal-inner",
        "#final-cta .fade-up",
      ]);
      return;
    }

    AnimationUtils.sectionLabel("#final-cta");
    AnimationUtils.textReveal("#final-cta .text-reveal-inner", {
      trigger: "#final-cta",
      start: "top 70%",
    });
    AnimationUtils.fadeUp("#final-cta .final-cta__subtitle", {
      trigger: "#final-cta",
      start: "top 70%",
      y: 30,
    });
    AnimationUtils.cards("#final-cta .final-cta__btn", {
      trigger: "#final-cta .final-cta__buttons",
      start: "top 70%",
      y: 20,
    });
    AnimationUtils.fadeUp("#final-cta .final-cta__phone", {
      trigger: "#final-cta .final-cta__phone",
      start: "top 75%",
      y: 15,
      duration: 0.6,
    });
  }

  // =========================================================
  // INITIALIZE
  // =========================================================

  function initFooterAnimations() {
    if (!elementExists("#footer")) return;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    const yearEl = document.querySelector("[data-current-year]");
    if (yearEl) {
      yearEl.textContent = new Date().getFullYear();
    }

    const newsletterForm = document.querySelector("[data-newsletter-form]");
    if (newsletterForm && newsletterForm.dataset.newsletterBound !== "1") {
      newsletterForm.dataset.newsletterBound = "1";
      newsletterForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const input = newsletterForm.querySelector(".footer__newsletter-input");
        const btn = newsletterForm.querySelector(
          ".footer__newsletter-btn span",
        );
        if (!input || !input.value || !btn) return;
        const originalText = btn.textContent;
        try {
          const res = await fetch(newsletterForm.action, {
            method: "POST",
            headers: {
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest",
            },
            body: new FormData(newsletterForm),
          });
          if (res.ok) {
            btn.textContent = "Subscribed ✓";
            input.value = "";
          }
        } catch (_) {
          /* keep button usable */
        }
        setTimeout(() => {
          btn.textContent = originalText;
        }, 2500);
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
        {
          selector: ".accreditations__trust",
          y: 20,
          duration: 0.6,
          delay: 0.3,
        },
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

    initGlobalAccessPointsAnimations();

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
    // Our Story page uses public/assets/js/our-story-animations.js
    if (window.location.pathname.includes("our-story")) return;
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
      if (window.location.pathname.includes("our-story")) return;
      console.warn("lenisReady never fired – starting animations fallback");
      startAnimations();
    }
  }, 800);
})();
