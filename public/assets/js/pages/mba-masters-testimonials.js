/**
 * Luxury single-testimonial carousel — accessible, pauseable, and lightweight.
 */
(function () {
  "use strict";

  var carousel = document.querySelector("[data-luxury-testimonials]");
  if (!carousel) return;

  var slides = Array.prototype.slice.call(carousel.querySelectorAll("[data-testimonial-slide]"));
  var previous = carousel.querySelector("[data-testimonial-prev]");
  var next = carousel.querySelector("[data-testimonial-next]");
  var toggle = carousel.querySelector("[data-testimonial-toggle]");
  var current = carousel.querySelector("[data-testimonial-current]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var activeIndex = 0;
  var paused = reduced;
  var hovered = false;
  var focused = false;
  var timer = null;
  var resumeTimer = null;
  var startX = null;

  function updateToggle() {
    if (!toggle) return;
    toggle.setAttribute("aria-pressed", paused ? "true" : "false");
    toggle.textContent = paused ? "Play" : "Pause";
  }

  function showSlide(index) {
    if (!slides.length) return;
    activeIndex = (index + slides.length) % slides.length;

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

    if (current) current.textContent = String(activeIndex + 1).padStart(2, "0");
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
      if (!paused && !hovered && !focused) goNext();
    }, 6000);
  }

  if (previous) previous.addEventListener("click", function () { goPrevious(); scheduleResume(); });
  if (next) next.addEventListener("click", function () { goNext(); scheduleResume(); });
  if (toggle) {
    toggle.addEventListener("click", function () {
      paused = !paused;
      updateToggle();
      startTimer();
    });
  }

  carousel.addEventListener("mouseenter", function () { hovered = true; });
  carousel.addEventListener("mouseleave", function () { hovered = false; });
  carousel.addEventListener("focusin", function () { focused = true; });
  carousel.addEventListener("focusout", function () { focused = false; });
  carousel.addEventListener("keydown", function (event) {
    if (event.key === "ArrowLeft") {
      event.preventDefault();
      goPrevious();
      scheduleResume();
    }
    if (event.key === "ArrowRight") {
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
  carousel.addEventListener("pointercancel", function () { startX = null; });

  showSlide(0);
  updateToggle();
  startTimer();

  window.addEventListener("beforeunload", function () {
    window.clearInterval(timer);
    window.clearTimeout(resumeTimer);
  }, { once: true });
})();
