(function () {
  "use strict";

  // Read more / Read less accordion for faculty-insight cards.
  // Only one card expanded at a time; the toggle appears only
  // when the text actually overflows the 4-line clamp.

  function setState(btn, excerpt, open) {
    btn.setAttribute("aria-expanded", open ? "true" : "false");
    btn.textContent = open ? "Read less" : "Read more";
    btn.hidden = false;
    if (excerpt) excerpt.classList.toggle("insights__card-excerpt--clamp", !open);
  }

  function evaluate(card) {
    var excerpt = card.querySelector("[data-fi-excerpt]");
    var btn = card.querySelector("[data-fi-toggle]");
    if (!excerpt || !btn) return;

    // Keep expanded cards expanded (e.g. across resize/font load).
    if (btn.getAttribute("aria-expanded") === "true") {
      btn.hidden = false;
      return;
    }

    excerpt.classList.add("insights__card-excerpt--clamp");
    btn.hidden = excerpt.scrollHeight <= excerpt.clientHeight + 2;
  }

  function bind(card) {
    var btn = card.querySelector("[data-fi-toggle]");
    if (!btn || btn.dataset.fiInit) return;
    btn.dataset.fiInit = "1";

    btn.addEventListener("click", function () {
      var excerpt = document.getElementById(btn.getAttribute("data-fi-toggle"));
      var willOpen = btn.getAttribute("aria-expanded") !== "true";

      // Accordion: collapse any other open card first.
      document.querySelectorAll('[data-fi-toggle][aria-expanded="true"]').forEach(function (other) {
        if (other === btn) return;
        setState(other, document.getElementById(other.getAttribute("data-fi-toggle")), false);
      });

      setState(btn, excerpt, willOpen);

      if (window.ScrollTrigger) window.ScrollTrigger.refresh();
    });
  }

  function init() {
    var cards = document.querySelectorAll("[data-fi-card]");
    if (!cards.length) return;
    cards.forEach(bind);
    cards.forEach(evaluate);
  }

  if (document.readyState !== "loading") init();
  else document.addEventListener("DOMContentLoaded", init);

  // Re-evaluate after fonts settle (line heights shift) and on resize.
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(init);
  var resizeTimer = null;
  window.addEventListener("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(init, 150);
  });
})();
