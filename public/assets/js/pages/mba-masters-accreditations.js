/* Landing accreditation logos — lightweight loop for the shared section. */
(function () {
  "use strict";

  function init() {
    var section = document.querySelector("#accreditations");
    var wrapper = section && section.querySelector(".accred-slider-wrapper");
    var track = wrapper && wrapper.querySelector(".accred-slider-track");

    if (!track || track.dataset.mlpAccreditationSlider === "1") return;
    track.dataset.mlpAccreditationSlider = "1";

    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

    var cards = Array.prototype.slice.call(track.children);
    if (cards.length < 2) return;

    cards.forEach(function (card) {
      var clone = card.cloneNode(true);
      clone.setAttribute("aria-hidden", "true");
      track.appendChild(clone);
    });

    track.classList.add("is-landing-slider");
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
