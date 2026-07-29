(function () {
  "use strict";

  if (!window.location.pathname.includes("our-story")) return;
  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
    console.warn("Our Story: GSAP or ScrollTrigger not loaded.");
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  /* ─── Utilities ─── */
  function el(s) { return document.querySelector(s); }
  function els(s) { return document.querySelectorAll(s); }
  function isMobile() { return window.innerWidth < 1024; }
  function prefersRM() { return window.matchMedia("(prefers-reduced-motion: reduce)").matches; }

  function cleanupAll() { ScrollTrigger.getAll().forEach(t => t.kill()); }
  window.addEventListener("beforeunload", cleanupAll);

  /* ─── Generic reveal for .fade-up & .text-reveal-inner ─── */
  function revealSection(selector) {
    const section = document.querySelector(selector);
    if (!section) return;

    const textInners = section.querySelectorAll(".text-reveal-inner");
    const fadeUps = section.querySelectorAll(".fade-up");

    if (prefersRM()) {
      gsap.set(textInners, { y: "0%", clearProps: "transform" });
      gsap.set(fadeUps, { opacity: 1, y: 0, clearProps: "transform" });
      return;
    }

    if (textInners.length) {
      gsap.set(textInners, { y: "110%" });
      gsap.to(textInners, {
        y: "0%", duration: 0.9, stagger: 0.12, ease: "power3.out",
        scrollTrigger: { trigger: section, start: "top 78%", once: true }
      });
    }
    if (fadeUps.length) {
      gsap.set(fadeUps, { opacity: 0, y: isMobile() ? 24 : 40 });
      gsap.to(fadeUps, {
        opacity: 1, y: 0, duration: 0.7, stagger: 0.1, ease: "power2.out",
        scrollTrigger: { trigger: section, start: "top 78%", once: true }
      });
    }
  }

  /* ─── Counter animation ─── */
  function animateCounter(element, target, dur) {
    const obj = { value: 0 };
    gsap.to(obj, {
      value: target, duration: dur, ease: "power2.out",
      onUpdate() { element.textContent = Math.round(obj.value).toLocaleString("en-US"); },
      onComplete() { element.textContent = target.toLocaleString("en-US"); }
    });
  }

  /* =========================================================
     HERO
     ========================================================= */
  function initHero() {
    const hero = el("#story-hero");
    if (!hero) return;

    const fadeUps = hero.querySelectorAll(".fade-up");
    if (prefersRM()) { gsap.set(fadeUps, { opacity: 1, y: 0 }); return; }

    gsap.set(fadeUps, { opacity: 0, y: 30 });
    const tl = gsap.timeline({ delay: 0.3 });
    tl.to(fadeUps, { opacity: 1, y: 0, duration: 0.8, stagger: 0.15, ease: "power3.out" });

    /* Parallax shapes */
    hero.querySelectorAll(".os-hero__shape").forEach((shape, i) => {
      gsap.to(shape, {
        y: (i + 1) * -40, ease: "none",
        scrollTrigger: { trigger: hero, start: "top top", end: "bottom top", scrub: 1.5 }
      });
    });

    /* Content parallax */
    const content = hero.querySelector(".os-hero__content");
    if (content) {
      gsap.to(content, {
        y: -50, ease: "none",
        scrollTrigger: { trigger: hero, start: "top top", end: "bottom top", scrub: 1 }
      });
    }
  }

  /* =========================================================
     BEGINNING — image slide-in
     ========================================================= */
  function initBeginning() {
    if (!el("#beginning")) return;
    revealSection("#beginning");
    if (prefersRM()) return;

    const img = el(".os-beginning__image-wrap");
    if (img) {
      gsap.fromTo(img, { x: 60, opacity: 0 }, {
        x: 0, opacity: 1, duration: 1, ease: "power3.out",
        scrollTrigger: { trigger: "#beginning", start: "top 70%", once: true }
      });
    }
    const accent = el(".os-beginning__image-accent");
    if (accent) {
      gsap.fromTo(accent, { scale: 0.8, opacity: 0 }, {
        scale: 1, opacity: 0.12, duration: 0.8, delay: 0.2, ease: "power2.out",
        scrollTrigger: { trigger: "#beginning", start: "top 70%", once: true }
      });
    }
  }

  /* =========================================================
     TODAY
     ========================================================= */
  function initToday() {
    if (!el("#today")) return;
    revealSection("#today");
    if (prefersRM()) return;

    const img = el(".os-today__image-wrap");
    if (img) {
      gsap.fromTo(img, { x: -60, opacity: 0 }, {
        x: 0, opacity: 1, duration: 1, ease: "power3.out",
        scrollTrigger: { trigger: "#today", start: "top 70%", once: true }
      });
    }
    const pills = els(".os-today__pill");
    if (pills.length) {
      gsap.fromTo(pills, { opacity: 0, y: 15 }, {
        opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: "power2.out",
        scrollTrigger: { trigger: ".os-today__pills", start: "top 85%", once: true }
      });
    }
  }

  /* =========================================================
     IMPACT — counter + reveals
     ========================================================= */
  function initImpact() {
    const section = el("#impact");
    if (!section) return;
    revealSection("#impact");
    if (prefersRM()) return;

    section.querySelectorAll("[data-counter-target]").forEach(card => {
      const target = parseInt(card.getAttribute("data-counter-target"), 10);
      const counterEl = card.querySelector("[data-counter]");
      if (isNaN(target) || !counterEl) return;
      ScrollTrigger.create({
        trigger: card, start: "top 85%", once: true,
        onEnter: () => animateCounter(counterEl, target, target >= 1000 ? 2 : 1.5)
      });
    });
  }

  /* =========================================================
     VISION
     ========================================================= */
  function initVision() {
    if (!el("#vision")) return;
    revealSection("#vision");
  }

  /* =========================================================
     JOURNEY — Horizontal pinned scroll (Desktop only)
     ========================================================= */
  function initJourney() {
    const pinWrap = el("[data-journey-pin]");
    const track = el("[data-journey-track]");
    if (!pinWrap || !track) return;

    if (isMobile()) {
      /* Mobile: simple reveal */
      revealSection("#journey");
      els(".os-journey__mobile-card").forEach(card => {
        gsap.fromTo(card, { opacity: 0, y: 40 }, {
          opacity: 1, y: 0, duration: 0.7, ease: "power2.out",
          scrollTrigger: { trigger: card, start: "top 82%", once: true }
        });
      });
      return;
    }

    if (prefersRM()) return;

    const slides = els("[data-journey-slide]");
    const dots = els("[data-journey-dot]");
    const hint = el("[data-journey-hint]");
    const totalSlides = slides.length;
    if (!totalSlides) return;

    const scrollDistance = (totalSlides - 1) * window.innerWidth;

    /* Pin + horizontal scroll */
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: pinWrap,
        start: "top top",
        end: "+=" + scrollDistance,
        pin: true,
        scrub: 1,
        anticipatePin: 1,
        invalidateOnRefresh: true,
        onUpdate: self => {
          const progress = self.progress;
          const activeIdx = Math.round(progress * (totalSlides - 1));
          dots.forEach((d, i) => d.classList.toggle("is-active", i === activeIdx));
          if (hint) hint.style.opacity = progress > 0.05 ? "0" : "1";
        }
      }
    });

    tl.to(track, {
      x: () => -(track.scrollWidth - window.innerWidth),
      ease: "none"
    });

    /* Per-slide animations */
    slides.forEach((slide, i) => {
      const title = slide.querySelector(".os-journey__slide-title");
      const desc = slide.querySelector(".os-journey__slide-desc");
      const badge = slide.querySelector(".os-journey__year-badge");
      const img = slide.querySelector(".os-journey__slide-image");
      const bigYear = slide.querySelector(".os-journey__big-year");

      const enterPoint = i / totalSlides;
      const exitPoint = (i + 0.8) / totalSlides;

      if (badge) {
        gsap.fromTo(badge, { scale: 0.7, opacity: 0 },
          { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.4)",
            scrollTrigger: { trigger: pinWrap, start: "top top", end: "+=" + scrollDistance, scrub: 1,
              onUpdate: self => { const p = self.progress; badge.style.opacity = (p >= enterPoint && p <= exitPoint) ? 1 : 0; }
            }
          });
      }

      if (img) {
        gsap.fromTo(img, { scale: 0.8, opacity: 0 },
          { scale: 1, opacity: 1, duration: 0.6, ease: "power2.out",
            scrollTrigger: { trigger: pinWrap, start: "top top", end: "+=" + scrollDistance, scrub: 1 }
          });
      }

      if (bigYear) {
        gsap.to(bigYear, {
          x: -80, ease: "none",
          scrollTrigger: { trigger: pinWrap, start: "top top", end: "+=" + scrollDistance, scrub: 2 }
        });
      }
    });
  }

  /* =========================================================
     CEO MESSAGE (shared section)
     ========================================================= */
  function initCEO() {
    if (!el("#ceo-message")) return;

    const textReveals = els("#ceo-message .text-reveal-inner");
    if (textReveals.length && !prefersRM()) {
      gsap.fromTo(textReveals, { y: "110%" }, {
        y: "0%", duration: 0.9, stagger: 0.12, ease: "power3.out",
        scrollTrigger: { trigger: "#ceo-message", start: "top 70%", once: true }
      });
    }

    const tl = gsap.timeline({
      scrollTrigger: { trigger: "#ceo-message", start: "top 75%", once: true }
    });
    tl.fromTo(".ceo__image-wrapper", { opacity: 0, scale: 0.95 },
      { opacity: 1, scale: 1, duration: 0.9, ease: "power2.out" });
    tl.fromTo([".ceo__quote", ".ceo__body", ".ceo__signature", "#ceo-message .section-label"],
      { opacity: 0, y: 40 },
      { opacity: 1, y: 0, stagger: 0.15, duration: 0.8, ease: "power2.out" }, "-=0.4");
  }

  /* =========================================================
     GALLERY — stagger reveal + lightbox
     ========================================================= */
  function initGallery() {
    const section = el("#gallery");
    if (!section) return;

    revealSection("#gallery");

    const items = els("[data-gallery-item]");
    if (items.length && !prefersRM()) {
      gsap.fromTo(items, { opacity: 0, y: 30 }, {
        opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: "power2.out",
        scrollTrigger: { trigger: "[data-gallery-grid]", start: "top 80%", once: true }
      });
    }

    /* Lightbox */
    const lightbox = el("#os-lightbox");
    const lbImg = el("#os-lightbox-img");
    const lbCaption = el("#os-lightbox-caption");
    if (!lightbox || !lbImg) return;

    let currentIdx = 0;
    const images = [];

    items.forEach((item, i) => {
      const img = item.querySelector(".os-gallery__img");
      const captionEl = item.querySelector(".os-gallery__caption-text");
      if (img) {
        images.push({ src: img.src, alt: img.alt, caption: captionEl ? captionEl.textContent : "" });
        item.addEventListener("click", () => openLB(i));
      }
    });

    function openLB(idx) {
      currentIdx = idx;
      updateLB();
      lightbox.classList.add("is-open");
      lightbox.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
    }
    function closeLB() {
      lightbox.classList.remove("is-open");
      lightbox.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "";
    }
    function updateLB() {
      if (!images[currentIdx]) return;
      lbImg.src = images[currentIdx].src;
      lbImg.alt = images[currentIdx].alt;
      if (lbCaption) lbCaption.textContent = images[currentIdx].caption;
    }

    const closeBtn = el("[data-lightbox-close]");
    const prevBtn = el("[data-lightbox-prev]");
    const nextBtn = el("[data-lightbox-next]");

    if (closeBtn) closeBtn.addEventListener("click", closeLB);
    if (prevBtn) prevBtn.addEventListener("click", () => { currentIdx = (currentIdx - 1 + images.length) % images.length; updateLB(); });
    if (nextBtn) nextBtn.addEventListener("click", () => { currentIdx = (currentIdx + 1) % images.length; updateLB(); });

    lightbox.addEventListener("click", e => { if (e.target === lightbox) closeLB(); });
    document.addEventListener("keydown", e => {
      if (!lightbox.classList.contains("is-open")) return;
      if (e.key === "Escape") closeLB();
      if (e.key === "ArrowLeft" && prevBtn) prevBtn.click();
      if (e.key === "ArrowRight" && nextBtn) nextBtn.click();
    });
  }

  /* =========================================================
     FINAL CTA (shared)
     ========================================================= */
  function initFinalCTA() {
    if (!el("#final-cta")) return;
    if (prefersRM()) {
      gsap.set(["#final-cta .text-reveal-inner", "#final-cta .fade-up"], { clearProps: "all", opacity: 1 });
      gsap.set("#final-cta .text-reveal-inner", { y: "0%" });
      return;
    }

    const label = el("#final-cta .section-label");
    if (label) gsap.fromTo(label, { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.6, ease: "power2.out",
      scrollTrigger: { trigger: "#final-cta", start: "top 70%", once: true } });

    const heading = el("#final-cta .text-reveal-inner");
    if (heading) gsap.fromTo(heading, { y: "110%" }, { y: "0%", duration: 0.9, ease: "power3.out",
      scrollTrigger: { trigger: "#final-cta", start: "top 70%", once: true } });

    const sub = el("#final-cta .final-cta__subtitle");
    if (sub) gsap.fromTo(sub, { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8, ease: "power2.out",
      scrollTrigger: { trigger: "#final-cta", start: "top 70%", once: true } });

    const btns = els("#final-cta .final-cta__btn");
    if (btns.length) gsap.fromTo(btns, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, ease: "power2.out",
      scrollTrigger: { trigger: "#final-cta .final-cta__buttons", start: "top 70%", once: true } });
  }

  /* =========================================================
     FOOTER
     ========================================================= */
  function initFooter() {
    if (!el("#footer")) return;
    const yearEl = el("[data-current-year]");
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    const form = el("[data-newsletter-form]");
    if (form) {
      form.addEventListener("submit", e => {
        e.preventDefault();
        const input = form.querySelector(".footer__newsletter-input");
        const btn = form.querySelector(".footer__newsletter-btn span");
        if (input && input.value && btn) {
          const orig = btn.textContent;
          btn.textContent = "Subscribed";
          input.value = "";
          setTimeout(() => { btn.textContent = orig; }, 2500);
        }
      });
    }

    if (prefersRM()) return;
    const cols = els(".footer__col");
    if (cols.length) {
      gsap.set(cols, { opacity: 0, y: 30 });
      gsap.to(cols, { opacity: 1, y: 0, duration: 0.7, stagger: 0.12, ease: "power2.out",
        scrollTrigger: { trigger: "#footer", start: "top 85%", once: true } });
    }
  }

  /* =========================================================
     INIT
     ========================================================= */
  function init() {
    if (window.__ourStoryAnimationsStarted) return;
    window.__ourStoryAnimationsStarted = true;
    window.__animationsStarted = true;

    initHero();
    initBeginning();
    initToday();
    initImpact();
    initVision();
    initJourney();
    initCEO();
    initGallery();
    initFinalCTA();
    initFooter();

    ScrollTrigger.refresh();
  }

  if (window.__lenisReady) {
    init();
  } else {
    document.addEventListener("lenisReady", init, { once: true });
  }

  setTimeout(function () {
    if (!window.__ourStoryAnimationsStarted) {
      console.warn("Our Story: lenisReady fallback triggered");
      init();
    }
  }, 800);
})();
