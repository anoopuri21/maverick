(function () {
  "use strict";

  function prefersReduced() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  function initPartnerWall(wall) {
    if (!wall || wall.dataset.mlpPartnerWall === "1") return;
    wall.dataset.mlpPartnerWall = "1";

    var track = wall.querySelector("[data-partner-track]");
    var toggle = wall.querySelector("[data-partner-toggle]");
    if (!track) return;

    track.classList.add("is-marquee");
    var reduced = prefersReduced();

    function setPaused(value) {
      wall.classList.toggle("is-paused", value);
      if (toggle) {
        toggle.setAttribute("aria-pressed", value ? "true" : "false");
        toggle.textContent = value ? "Play" : "Pause";
      }
    }

    if (toggle && !reduced) {
      toggle.addEventListener("click", function () {
        setPaused(!wall.classList.contains("is-paused"));
      });
    }

    if ("IntersectionObserver" in window) {
      var wallIo = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            wall.classList.toggle("is-offscreen", !entry.isIntersecting);
          });
        },
        { threshold: 0 }
      );
      wallIo.observe(wall);
    }

    setPaused(reduced);
  }

  document.querySelectorAll("[data-partner-wall]").forEach(initPartnerWall);
})();
