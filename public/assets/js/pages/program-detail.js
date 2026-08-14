/**
 * Programme detail page orchestrator
 * - Shared Our Story testimonials slider (reviews)
 * - Left-side scrollspy dots
 */

import { initTestimonialSlider } from "../components/testimonial-slider.js";

function prefersReducedMotion() {
  return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}

/**
 * Left-side scrollspy dots.
 * - Builds one dot per *rendered* section (dots already match content in
 *   blade via $sectionNav; here we only wire the active state).
 * - Active dot = section currently in view. Works on scroll AND on initial
 *   load (IntersectionObserver fires once for whatever is visible).
 * - No scroll listeners → cheap, and honours prefers-reduced-motion by
 *   relying on the browser's native scroll + IO.
 */
function initScrollSpy(rail) {
  if (!rail || rail.dataset.pdDotsInit === "true") return;
  rail.dataset.pdDotsInit = "true";

  const items = Array.from(rail.querySelectorAll("[data-pd-dot]"));
  if (!items.length) return;

  const byId = new Map(items.map((item) => [item.dataset.pdDot, item]));
  const sections = items
    .map((item) => document.getElementById(item.dataset.pdDot))
    .filter(Boolean);
  if (!sections.length) return;

  let active = "";

  const setActive = (id) => {
    if (id === active) return;
    active = id;
    items.forEach((item) => {
      item.classList.toggle("is-active", item.dataset.pdDot === id);
    });
  };

  // Pick the top-most section that crosses the top of the viewport.
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) setActive(entry.target.id);
      });
    },
    // A thin band near the top of the viewport is the "active" zone.
    { rootMargin: "-15% 0px -75% 0px", threshold: 0 },
  );

  sections.forEach((sec) => observer.observe(sec));

  // Tidy initial state: if we loaded mid-page (e.g. after refresh / anchor),
  // IO may not fire for the section already in view until a scroll. Force a
  // scan once so the correct dot is active immediately.
  const scanOnce = () => {
    const probe = document.elementFromPoint(
      window.innerWidth / 2,
      Math.min(window.innerHeight * 0.3, window.scrollY + window.innerHeight * 0.25) || window.innerHeight * 0.25,
    );
    const hit = probe?.closest("section[id]");
    if (hit) setActive(hit.id);
  };
  window.setTimeout(scanOnce, 60);

  // Smooth-scroll + set active immediately on dot click (nice-to-have).
  items.forEach((item) => {
    item.addEventListener("click", (e) => {
      const target = document.getElementById(item.dataset.pdDot);
      if (!target) return;
      e.preventDefault();
      setActive(item.dataset.pdDot);
      const top = target.getBoundingClientRect().top + window.scrollY - 85;
      window.scrollTo({ top, behavior: prefersReducedMotion() ? "auto" : "smooth" });
    });
  });
}

/**
 * Pause the logo marquee when it is off-screen to save cycles.
 * Toggles animation-play-state via IntersectionObserver.
 */
function initMarquee(marquee) {
  if (marquee.dataset.pdMarqueeInit === "true") return;
  marquee.dataset.pdMarqueeInit = "true";

  const track = marquee.querySelector(".pd-logo-strip__track");
  if (!track) return;

  const reduced = prefersReducedMotion();
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (reduced) {
          track.style.animationPlayState = "paused";
          return;
        }
        track.style.animationPlayState = entry.isIntersecting ? "running" : "paused";
      });
    },
    { rootMargin: "0px 0px 0px 0px", threshold: 0 },
  );
  io.observe(marquee);
}

/** Google review cards: 8-line clamp with read more / read less toggle. */
function initGoogleReviews(scope) {
  scope.querySelectorAll("[data-clamp-toggle]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".os-testimonials__card");
      const expanded = card.classList.toggle("is-expanded");
      btn.setAttribute("aria-expanded", String(expanded));
      btn.textContent = expanded ? "Read less" : "Read more";
    });
  });
}

function initProgramDetail() {
  const reviews = document.querySelector("#pd-reviews");
  if (reviews) {
    // Google variant is a responsive grid, not an autoplay slider.
    if (reviews.classList.contains("os-testimonials--google")) {
      initGoogleReviews(reviews);
    } else {
      initTestimonialSlider("#pd-reviews");
    }
  }
  document.querySelectorAll("[data-pd-dots]").forEach(initScrollSpy);
  document.querySelectorAll("[data-pd-marquee]").forEach(initMarquee);
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initProgramDetail);
} else {
  initProgramDetail();
}
