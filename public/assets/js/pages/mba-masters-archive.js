/**
 * The Light Archive — Class, Career, Alumni, Learning, and Partners.
 * All section content is server-rendered; this module adds restrained progressive enhancement.
 */
(function () {
  "use strict";

  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function initArchiveReveals() {
    var targets = document.querySelectorAll(
      "[data-archive-class], [data-archive-career], [data-archive-learning], [data-archive-element]"
    );

    function reveal(target) {
      target.classList.add("is-inview");
    }

    if (reduced || !("IntersectionObserver" in window)) {
      targets.forEach(reveal);
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          reveal(entry.target);
          observer.unobserve(entry.target);
        });
      },
      { threshold: 0.16, rootMargin: "0px 0px -8% 0px" }
    );

    targets.forEach(function (target) {
      observer.observe(target);
    });
  }

  function initPartnerWall() {
    var wall = document.querySelector("[data-partner-wall]");
    if (!wall) return;

    var viewport = wall.querySelector("[data-partner-viewport]");
    var track = wall.querySelector("[data-partner-track]");
    var toggle = wall.querySelector("[data-partner-toggle]");
    if (!viewport || !track) return;

    var paused = reduced;
    var hovered = false;
    var focused = false;
    var dragging = false;
    var pointerId = null;
    var startX = 0;
    var startScroll = 0;
    var frameId = null;

    function setPaused(value) {
      paused = value;
      wall.classList.toggle("is-paused", paused);
      if (toggle) {
        toggle.setAttribute("aria-pressed", paused ? "true" : "false");
        toggle.textContent = paused ? "Play" : "Pause";
      }
    }

    function shouldMove() {
      return !reduced && !paused && !hovered && !focused && !dragging && track.scrollWidth > viewport.clientWidth;
    }

    function loop() {
      if (shouldMove()) {
        viewport.scrollLeft += 0.45;
        var loopWidth = track.scrollWidth / 2;
        if (loopWidth > 0 && viewport.scrollLeft >= loopWidth) {
          viewport.scrollLeft -= loopWidth;
        }
      }
      frameId = window.requestAnimationFrame(loop);
    }

    function stopDrag(event) {
      if (!dragging || (event && event.pointerId !== pointerId)) return;
      dragging = false;
      if (viewport.releasePointerCapture && pointerId !== null) {
        try { viewport.releasePointerCapture(pointerId); } catch (error) { /* already released */ }
      }
      pointerId = null;
      viewport.classList.remove("is-dragging");
    }

    viewport.addEventListener("pointerdown", function (event) {
      if (event.pointerType === "mouse" && event.button !== 0) return;
      dragging = true;
      pointerId = event.pointerId;
      startX = event.clientX;
      startScroll = viewport.scrollLeft;
      viewport.classList.add("is-dragging");
      if (viewport.setPointerCapture) viewport.setPointerCapture(pointerId);
    });

    viewport.addEventListener("pointermove", function (event) {
      if (!dragging || event.pointerId !== pointerId) return;
      viewport.scrollLeft = startScroll - (event.clientX - startX);
    });

    viewport.addEventListener("pointerup", stopDrag);
    viewport.addEventListener("pointercancel", stopDrag);
    viewport.addEventListener("mouseenter", function () { hovered = true; });
    viewport.addEventListener("mouseleave", function (event) {
      hovered = false;
      stopDrag(event);
    });
    viewport.addEventListener("focusin", function () { focused = true; });
    viewport.addEventListener("focusout", function () { focused = false; });
    viewport.addEventListener("keydown", function (event) {
      if (event.key === "ArrowLeft") {
        event.preventDefault();
        viewport.scrollBy({ left: -260, behavior: reduced ? "auto" : "smooth" });
      }
      if (event.key === "ArrowRight") {
        event.preventDefault();
        viewport.scrollBy({ left: 260, behavior: reduced ? "auto" : "smooth" });
      }
    });

    if (toggle) {
      toggle.addEventListener("click", function () {
        setPaused(!paused);
      });
    }

    setPaused(reduced);
    frameId = window.requestAnimationFrame(loop);

    window.addEventListener("beforeunload", function () {
      if (frameId) window.cancelAnimationFrame(frameId);
    }, { once: true });
  }

  function init() {
    initArchiveReveals();
    initPartnerWall();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
