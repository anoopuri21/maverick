/**
 * The Learning Blueprint — Overview section.
 * Decorative blueprint motion only; all programme foundations stay in HTML.
 */
(function () {
  "use strict";

  var system = document.querySelector("[data-overview-blueprint]");
  if (!system) return;

  var foundations = system.querySelectorAll("[data-overview-foundation]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function revealAll() {
    system.classList.add("is-inview");
    foundations.forEach(function (foundation) {
      foundation.classList.add("is-inview");
    });
  }

  if (reduced || !("IntersectionObserver" in window)) {
    revealAll();
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        revealAll();
        observer.disconnect();
      });
    },
    { threshold: 0.18, rootMargin: "0px 0px -8% 0px" }
  );

  observer.observe(system);
})();
