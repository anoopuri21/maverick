/**
 * The Signal Atlas — graphical trust record.
 * Keeps trust motion lightweight and independent from the locked Hero/Enquiry code.
 */
(function () {
  "use strict";

  var graph = document.querySelector("[data-signal-atlas]");
  if (!graph) return;

  var section = graph.closest(".signal-atlas");
  var records = graph.querySelectorAll("[data-signal-record]");
  var paths = graph.querySelectorAll("[data-signal-path]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function reveal() {
    if (section) section.classList.add("is-inview");
    records.forEach(function (record) {
      record.classList.add("is-inview");
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
    { threshold: 0.2, rootMargin: "0px 0px -8% 0px" }
  );

  observer.observe(graph);
})();
