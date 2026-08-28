/**
 * Quietly Cinematic Academy — page-wide polish coordinator.
 * It never hides content; it only marks sections as ready/in-view for CSS polish.
 */
(function () {
  "use strict";

  var page = document.querySelector(".mlp-page--polished");
  if (!page) return;

  page.classList.add("is-polished-ready");

  var sections = page.querySelectorAll(":scope > section");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function mark(section) {
    section.classList.add("is-polished-inview");
  }

  if (reduced || !("IntersectionObserver" in window)) {
    sections.forEach(mark);
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        mark(entry.target);
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.06, rootMargin: "0px 0px -6% 0px" }
  );

  sections.forEach(function (section) {
    observer.observe(section);
  });
})();
