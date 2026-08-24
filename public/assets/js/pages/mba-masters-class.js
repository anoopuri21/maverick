/**
 * The Cohort Portrait — Class Profile section.
 * Decorative activation only; metrics, regions, and industries remain in HTML.
 */
(function () {
  "use strict";

  var board = document.querySelector("[data-cohort-portrait]");
  if (!board) return;

  var elements = board.querySelectorAll("[data-cohort-element]");
  var industries = board.querySelectorAll("[data-cohort-industry]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function reveal() {
    board.classList.add("is-inview");
    elements.forEach(function (element) {
      element.classList.add("is-inview");
    });
    industries.forEach(function (industry) {
      industry.classList.add("is-inview");
    });
  }

  if (reduced || !("IntersectionObserver" in window)) {
    reveal();
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        reveal();
        observer.disconnect();
      });
    },
    { threshold: 0.14, rootMargin: "0px 0px -8% 0px" }
  );

  observer.observe(board);
})();
