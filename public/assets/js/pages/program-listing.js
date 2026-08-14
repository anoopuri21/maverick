/**
 * Programme listing — category filter + entrance.
 * - Sticky filter buttons persist the active category in the URL (?category=slug)
 *   via history.replaceState so the state survives refresh.
 * - Cards are filtered by data-category; a visible empty state appears when none match.
 * - Entrance uses the same [data-reveal] pattern as the detail page (3s fail-open).
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
      { rootMargin: "0px 0px -10% 0px", threshold: 0.05 },
    );
    cards.forEach((card) => io.observe(card));
  }
  // Fail-open: never hide cards if something goes wrong.
  window.setTimeout(() => {
    cards.forEach((card) => card.classList.add("is-revealed"));
  }, 3000);

  function apply(category) {
    let visible = 0;
    cards.forEach((card) => {
      const match =
        category === "all" || card.getAttribute("data-category") === category;
      card.hidden = !match;
      card.classList.toggle("is-hidden", !match);
      if (match) visible++;
    });

    buttons.forEach((btn) => {
      const active = btn.getAttribute("data-filter") === category;
      btn.classList.toggle("is-active", active);
      btn.setAttribute("aria-pressed", active ? "true" : "false");
    });

    // Empty state
    let empty = gridEl.querySelector(".pl-empty--filter");
    if (visible === 0 && !empty) {
      empty = document.createElement("p");
      empty.className = "pl-empty pl-empty--filter";
      empty.textContent = "No programmes in this category yet.";
      gridEl.appendChild(empty);
    } else if (visible > 0 && empty) {
      empty.remove();
    }
  }

  // Read initial filter from URL (default: all).
  const params = new URLSearchParams(window.location.search);
  const initial = params.get("category");
  const valid = initial && buttons.some((b) => b.getAttribute("data-filter") === initial);
  apply(valid ? initial : "all");

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const category = btn.getAttribute("data-filter");
      apply(category);
      const url = new URL(window.location.href);
      if (category === "all") url.searchParams.delete("category");
      else url.searchParams.set("category", category);
      history.replaceState({}, "", url);
    });
  });
})();
