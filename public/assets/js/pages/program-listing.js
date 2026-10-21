/**
 * Programme listing — category filter + pagination + entrance.
 * - Category filter buttons navigate to ?category=slug (full page reload)
 *   so server-side pagination and filtering stay in sync.
 * - Pagination links preserve the category query string.
 * - Cards use reveal-on-scroll (no GSAP dependency, reduced-motion safe).
 */

(function () {
  "use strict";

  const filterEl = document.querySelector("[data-pl-filter]");
  const gridEl = document.querySelector("[data-pl-grid]");
  if (!filterEl || !gridEl) return;

  const buttons = Array.from(filterEl.querySelectorAll(".pl-filter__btn"));
  const cards = Array.from(gridEl.querySelectorAll(".pl-card"));

  // Optional reveal-on-scroll (no GSAP dependency, reduced-motion safe).
  const prefersReduced =
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (!prefersReduced && "IntersectionObserver" in window) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-revealed");
            io.unobserve(entry.target);
          }
        });
      },
      { rootMargin: "0px 0px -10% 0px", threshold: 0.05 }
    );
    cards.forEach((card) => io.observe(card));
  }
  // Fail-open: never hide cards if something goes wrong.
  window.setTimeout(() => {
    cards.forEach((card) => card.classList.add("is-revealed"));
  }, 3000);

  // Category filter: navigate server-side so pagination + filter stay in sync.
  buttons.forEach((btn) => {
    const active = btn.getAttribute("aria-pressed") === "true";
    btn.classList.toggle("is-active", active);

    btn.addEventListener("click", () => {
      const category = btn.getAttribute("data-filter");
      const url = new URL(window.location.href);
      if (category === "all") {
        url.searchParams.delete("category");
      } else {
        url.searchParams.set("category", category);
      }
      // Reset to page 1 when changing category
      url.searchParams.delete("page");
      window.location.href = url.toString();
    });
  });
})();
