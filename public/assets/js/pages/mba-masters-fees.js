/**
 * The Fee Blueprint — compact programme/fee board.
 * Motion is progressive enhancement only; every fee remains in the table HTML.
 */
(function () {
  "use strict";

  var board = document.querySelector("[data-fee-blueprint]");
  if (!board) return;

  var rows = board.querySelectorAll("[data-fee-row]");
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function reveal() {
    rows.forEach(function (row) {
      row.classList.add("is-inview");
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
    { threshold: 0.12, rootMargin: "0px 0px -8% 0px" }
  );

  observer.observe(board);
})();
