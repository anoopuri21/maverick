/**
 * The Closing Archive — Fees, Voices, Parallel Brief, Field Notes, and Closing Desk.
 * Server-rendered content stays visible; this module only adds light activation.
 */
(function () {
  "use strict";

  var targets = document.querySelectorAll("[data-closing-element], [data-closing-voices]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

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
})();
