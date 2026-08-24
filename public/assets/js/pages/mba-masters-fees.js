/**
 * The Fee Cartography — compact graphical programme routes.
 * Motion is progressive enhancement only; every fee detail stays in HTML.
 */
(function () {
  "use strict";

  var map = document.querySelector("[data-fee-cartography]");
  if (!map) return;

  var routes = map.querySelectorAll("[data-fee-route]");
  var paths = map.querySelectorAll("[data-fee-path]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function reveal() {
    routes.forEach(function (route) {
      route.classList.add("is-inview");
    });
    paths.forEach(function (path) {
      path.style.strokeDashoffset = "0";
    });
  }

  if (reduced || !("IntersectionObserver" in window)) {
    reveal();
    return;
  }

  paths.forEach(function (path) {
    var length = 0;
    try {
      length = path.getTotalLength();
    } catch (error) {
      length = 1200;
    }
    path.style.strokeDasharray = length + " " + length;
    path.style.strokeDashoffset = length;
  });

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        reveal();
        observer.disconnect();
      });
    },
    { threshold: 0.12, rootMargin: "0px 0px -8% 0px" }
  );

  observer.observe(map);
})();
