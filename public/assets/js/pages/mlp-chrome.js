/* =====================================================================
   MBA/Master's Landing — chrome controller
   Scroll-spy for the single-page header: marks the anchor of the
   section currently in view with `.is-current` (underline).
   Anchor smooth-scroll itself is handled globally by main.js.
   ===================================================================== */

(function () {
  "use strict";

  const navbar = document.querySelector(".mlp-navbar");
  if (!navbar) return;

  const links = Array.from(navbar.querySelectorAll("[data-mlp-nav]"));
  if (!links.length) return;

  const sections = links
    .map((link) => ({
      link,
      el: document.querySelector(link.getAttribute("href") || ""),
    }))
    .filter((entry) => !!entry.el);

  if (!sections.length) return;

  const OFFSET = 140; // navbar height + breathing room
  let ticking = false;

  function activate(current) {
    links.forEach((link) => {
      link.classList.toggle("is-current", link === current);
    });
  }

  function update() {
    ticking = false;

    const y = window.scrollY + OFFSET;
    let current = null;

    for (const entry of sections) {
      if (entry.el.offsetTop <= y) current = entry;
    }

    // Bottom of the page: keep the last section active.
    const atBottom =
      window.innerHeight + window.scrollY >=
      document.documentElement.scrollHeight - 4;
    if (atBottom) current = sections[sections.length - 1];

    activate(current ? current.link : null);
  }

  function onScroll() {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }

  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", onScroll, { passive: true });

  // Immediate feedback on click (smooth scroll arrives later).
  links.forEach((link) => {
    link.addEventListener("click", () => activate(link));
  });

  update();
})();
