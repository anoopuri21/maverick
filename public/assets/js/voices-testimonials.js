/**
 * Maverick Business Academy — Standalone Voices Testimonials Controller
 *
 * Powers every `<x-voices-testimonials page="..." />` instance on any page:
 * - Multi-instance safe (`[data-voices-section]` / `[data-luxury-testimonials]`)
 * - Synchronized portrait stack crossfade & smart neighbor image preloading
 * - Autoplay with hover/focus/hidden-tab pause + Play/Pause toggle button
 * - Keyboard ArrowLeft / ArrowRight navigation & touch pointer swipe
 * - Optional GSAP scroll reveal when `window.gsap` is present, graceful CSS fallback otherwise
 */
(function () {
  "use strict";

  function prefersReducedMotion() {
    return Boolean(
      window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches
    );
  }

  function initCarouselInstance(carousel) {
    if (!carousel || carousel.dataset.voicesReady === "1") return;
    carousel.dataset.voicesReady = "1";

    var slides = Array.prototype.slice.call(
      carousel.querySelectorAll("[data-testimonial-slide]")
    );
    if (!slides.length) return;

    var stage = carousel.closest("[data-closing-voices]");
    var portraits = stage
      ? Array.prototype.slice.call(stage.querySelectorAll("[data-testimonial-portrait]"))
      : [];
    var previous = carousel.querySelector("[data-testimonial-prev]");
    var next = carousel.querySelector("[data-testimonial-next]");
    var toggle = carousel.querySelector("[data-testimonial-toggle]");
    var current = carousel.querySelector("[data-testimonial-current]");

    var reduced = prefersReducedMotion();
    var activeIndex = 0;
    var paused = reduced;
    var hovered = false;
    var focused = false;
    var pageHidden = Boolean(document.hidden);
    var timer = null;
    var resumeTimer = null;
    var startX = null;

    function updateToggle() {
      if (!toggle) return;
      toggle.setAttribute("aria-pressed", paused ? "true" : "false");
      toggle.textContent = paused ? "Play" : "Pause";
    }

    function preloadPortrait(index) {
      if (!portraits.length) return;
      var normalizedIndex = ((index % portraits.length) + portraits.length) % portraits.length;
      var image = portraits[normalizedIndex].querySelector("img");
      if (image) image.loading = "eager";
    }

    function showSlide(index) {
      activeIndex = ((index % slides.length) + slides.length) % slides.length;
      preloadPortrait(activeIndex);
      preloadPortrait(activeIndex - 1);
      preloadPortrait(activeIndex + 1);

      slides.forEach(function (slide, slideIndex) {
        var active = slideIndex === activeIndex;
        slide.hidden = !active;
        slide.setAttribute("aria-hidden", active ? "false" : "true");
        slide.classList.toggle("is-active", active);
        slide.classList.remove("is-entering");
        if (active && !reduced) {
          window.requestAnimationFrame(function () {
            slide.classList.add("is-entering");
          });
        }
      });

      portraits.forEach(function (portrait, portraitIndex) {
        portrait.classList.toggle("is-active", portraitIndex === activeIndex);
      });

      if (current) {
        current.textContent = String(activeIndex + 1).padStart(2, "0");
      }
    }

    function goNext() {
      showSlide(activeIndex + 1);
    }

    function goPrevious() {
      showSlide(activeIndex - 1);
    }

    function scheduleResume() {
      if (reduced) return;
      window.clearTimeout(resumeTimer);
      paused = true;
      updateToggle();
      resumeTimer = window.setTimeout(function () {
        if (!hovered && !focused) {
          paused = false;
          updateToggle();
        }
      }, 4200);
    }

    function startTimer() {
      if (reduced || slides.length < 2) return;
      window.clearInterval(timer);
      timer = window.setInterval(function () {
        if (!paused && !hovered && !focused && !pageHidden) {
          goNext();
        }
      }, 6000);
    }

    if (previous) {
      previous.addEventListener("click", function () {
        goPrevious();
        scheduleResume();
      });
    }

    if (next) {
      next.addEventListener("click", function () {
        goNext();
        scheduleResume();
      });
    }

    if (toggle) {
      toggle.addEventListener("click", function () {
        paused = !paused;
        updateToggle();
        startTimer();
      });
    }

    carousel.addEventListener("mouseenter", function () {
      hovered = true;
    });
    carousel.addEventListener("mouseleave", function () {
      hovered = false;
    });
    carousel.addEventListener("focusin", function () {
      focused = true;
    });
    carousel.addEventListener("focusout", function () {
      focused = false;
    });

    carousel.addEventListener("keydown", function (event) {
      if (event.key === "ArrowLeft") {
        event.preventDefault();
        goPrevious();
        scheduleResume();
      } else if (event.key === "ArrowRight") {
        event.preventDefault();
        goNext();
        scheduleResume();
      }
    });

    carousel.addEventListener("pointerdown", function (event) {
      startX = event.clientX;
      scheduleResume();
    });
    carousel.addEventListener("pointerup", function (event) {
      if (startX === null) return;
      var distance = event.clientX - startX;
      startX = null;
      if (Math.abs(distance) > 44) {
        if (distance < 0) goNext();
        else goPrevious();
      }
    });
    carousel.addEventListener("pointercancel", function () {
      startX = null;
    });

    function onVisibilityChange() {
      pageHidden = Boolean(document.hidden);
    }
    document.addEventListener("visibilitychange", onVisibilityChange);

    showSlide(0);
    updateToggle();
    startTimer();

    window.addEventListener(
      "beforeunload",
      function () {
        window.clearInterval(timer);
        window.clearTimeout(resumeTimer);
        document.removeEventListener("visibilitychange", onVisibilityChange);
      },
      { once: true }
    );
  }

  function initSectionReveal(section) {
    if (!section || section.dataset.voicesRevealInit === "1") return;
    section.dataset.voicesRevealInit = "1";

    var inViewTargets = Array.prototype.slice.call(
      section.querySelectorAll("[data-closing-voices], [data-closing-element]")
    );

    if (prefersReducedMotion() || !("IntersectionObserver" in window)) {
      inViewTargets.forEach(function (el) {
        el.classList.add("is-in-view");
      });
      return;
    }

    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          io.disconnect();
          inViewTargets.forEach(function (el) {
            el.classList.add("is-in-view");
          });

          if (typeof window.gsap !== "undefined") {
            var head = section.querySelector('[data-mlp-reveal="testimonials-head"]');
            var rail = section.querySelector('[data-mlp-reveal="testimonials-rail"]');
            var targets = [head, rail].filter(Boolean);
            if (targets.length) {
              window.gsap.fromTo(
                targets,
                { autoAlpha: 0, y: 22 },
                {
                  autoAlpha: 1,
                  y: 0,
                  duration: 0.65,
                  stagger: 0.1,
                  ease: "power3.out",
                  clearProps: "transform,opacity,visibility",
                }
              );
            }
          }
        });
      },
      { rootMargin: "120px 0px", threshold: 0.05 }
    );

    io.observe(section);
  }

  function initAllVoicesSections() {
    var sections = document.querySelectorAll("[data-voices-section], .mlp-testimonials");
    Array.prototype.forEach.call(sections, function (section) {
      initSectionReveal(section);
    });

    var carousels = document.querySelectorAll("[data-luxury-testimonials]");
    Array.prototype.forEach.call(carousels, function (carousel) {
      initCarouselInstance(carousel);
    });
  }

  window.MaverickVoicesTestimonials = {
    init: initAllVoicesSections,
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllVoicesSections);
  } else {
    initAllVoicesSections();
  }
})();
