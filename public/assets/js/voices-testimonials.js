/**
 * Maverick Business Academy — Standalone Voices Testimonials Controller
 *
 * Powers every `<x-voices-testimonials page="..." />` instance on any page:
 * - Multi-instance safe (`[data-voices-section]` / `[data-luxury-testimonials]`)
 * - Synchronized portrait stack crossfade & smart neighbor image preloading
 * - Autoplay with hover, focus, hidden-tab & reduced-motion pause + Play/Pause toggle
 * - Accessible keyboard ArrowLeft / ArrowRight navigation
 * - Direction-disambiguated horizontal touch swipe (ignores vertical scroll & text selection)
 * - Clean `destroy()` lifecycle support for SPA / Livewire re-renders
 */
(function () {
  "use strict";

  var instances = [];

  function prefersReducedMotion() {
    return Boolean(
      window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches
    );
  }

  function hasActiveTextSelection() {
    if (!window.getSelection) return false;
    var selection = window.getSelection();
    return Boolean(selection && String(selection.toString()).trim().length > 0);
  }

  function initCarouselInstance(carousel) {
    if (!carousel || carousel.dataset.voicesReady === "1") return null;
    carousel.dataset.voicesReady = "1";

    var slides = Array.prototype.slice.call(
      carousel.querySelectorAll("[data-testimonial-slide]")
    );
    if (!slides.length) return null;

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
    var startY = null;

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

        if (active) {
          var quoteBox = slide.querySelector(".luxury-testimonial__quote");
          if (quoteBox && typeof quoteBox.scrollTop === "number") {
            quoteBox.scrollTop = 0;
          }
          if (!reduced) {
            window.requestAnimationFrame(function () {
              slide.classList.add("is-entering");
            });
          }
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
      }, 4500);
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

    function onPrevClick() {
      goPrevious();
      scheduleResume();
    }

    function onNextClick() {
      goNext();
      scheduleResume();
    }

    function onToggleClick() {
      paused = !paused;
      updateToggle();
      startTimer();
    }

    function onMouseEnter() {
      hovered = true;
    }

    function onMouseLeave() {
      hovered = false;
    }

    function onFocusIn() {
      focused = true;
    }

    function onFocusOut() {
      focused = false;
    }

    function onKeyDown(event) {
      if (event.key === "ArrowLeft") {
        event.preventDefault();
        goPrevious();
        scheduleResume();
      } else if (event.key === "ArrowRight") {
        event.preventDefault();
        goNext();
        scheduleResume();
      }
    }

    function onPointerDown(event) {
      startX = event.clientX;
      startY = event.clientY != null ? event.clientY : 0;
      scheduleResume();
    }

    function onPointerUp(event) {
      if (startX === null) return;
      var dx = event.clientX - startX;
      var dy = (event.clientY != null ? event.clientY : 0) - (startY || 0);
      startX = null;
      startY = null;

      if (hasActiveTextSelection()) return;

      if (Math.abs(dx) > 44 && Math.abs(dx) > Math.abs(dy) * 1.4) {
        if (dx < 0) goNext();
        else goPrevious();
      }
    }

    function onPointerCancel() {
      startX = null;
      startY = null;
    }

    function onVisibilityChange() {
      pageHidden = Boolean(document.hidden);
    }

    if (previous) previous.addEventListener("click", onPrevClick);
    if (next) next.addEventListener("click", onNextClick);
    if (toggle) toggle.addEventListener("click", onToggleClick);

    carousel.addEventListener("mouseenter", onMouseEnter);
    carousel.addEventListener("mouseleave", onMouseLeave);
    carousel.addEventListener("focusin", onFocusIn);
    carousel.addEventListener("focusout", onFocusOut);
    carousel.addEventListener("keydown", onKeyDown);
    carousel.addEventListener("pointerdown", onPointerDown);
    carousel.addEventListener("pointerup", onPointerUp);
    carousel.addEventListener("pointercancel", onPointerCancel);
    document.addEventListener("visibilitychange", onVisibilityChange);

    showSlide(0);
    updateToggle();
    startTimer();

    function destroy() {
      window.clearInterval(timer);
      window.clearTimeout(resumeTimer);
      if (previous) previous.removeEventListener("click", onPrevClick);
      if (next) next.removeEventListener("click", onNextClick);
      if (toggle) toggle.removeEventListener("click", onToggleClick);
      carousel.removeEventListener("mouseenter", onMouseEnter);
      carousel.removeEventListener("mouseleave", onMouseLeave);
      carousel.removeEventListener("focusin", onFocusIn);
      carousel.removeEventListener("focusout", onFocusOut);
      carousel.removeEventListener("keydown", onKeyDown);
      carousel.removeEventListener("pointerdown", onPointerDown);
      carousel.removeEventListener("pointerup", onPointerUp);
      carousel.removeEventListener("pointercancel", onPointerCancel);
      document.removeEventListener("visibilitychange", onVisibilityChange);
      delete carousel.dataset.voicesReady;
    }

    window.addEventListener("beforeunload", destroy, { once: true });

    var handle = { carousel: carousel, destroy: destroy };
    instances.push(handle);
    return handle;
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
    Array.prototype.forEach.call(sections, initSectionReveal);

    var carousels = document.querySelectorAll("[data-luxury-testimonials]");
    Array.prototype.forEach.call(carousels, initCarouselInstance);
  }

  function destroyAllVoicesSections() {
    while (instances.length) {
      var handle = instances.pop();
      if (handle && typeof handle.destroy === "function") {
        handle.destroy();
      }
    }
  }

  window.MaverickVoicesTestimonials = {
    init: initAllVoicesSections,
    destroy: destroyAllVoicesSections,
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllVoicesSections);
  } else {
    initAllVoicesSections();
  }
})();
