/**
 * Programme detail page orchestrator
 * - Shared Our Story testimonials slider (reviews)
 * - Count-aware recognition sliders (manual + arrows + snap, no autoplay)
 */

import { initTestimonialSlider } from "../components/testimonial-slider.js";

function prefersReducedMotion() {
  return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}

/**
 * Reusable snap slider for Awarded By / Recognised groups.
 * 3+ items → arrows + snap scroll. 1–2 items → static grid.
 * Works without JS (native overflow). Reduced-motion uses instant scroll.
 */
function initPdSlider(root) {
  if (!root || root.dataset.pdSliderInit === "true") return;
  root.dataset.pdSliderInit = "true";

  const scroller = root.querySelector("[data-pd-scroller]");
  const track = root.querySelector(".pd-slider__track");
  const prev = root.querySelector("[data-pd-prev]");
  const next = root.querySelector("[data-pd-next]");
  const items = track ? Array.from(track.children) : [];
  const min = Number(root.dataset.pdMin || 3);

  const makeStatic = () => {
    root.classList.add("is-static");
    if (prev) {
      prev.hidden = true;
      prev.disabled = true;
    }
    if (next) {
      next.hidden = true;
      next.disabled = true;
    }
  };

  if (!scroller || !track || items.length < min) {
    makeStatic();
    return;
  }

  const smooth = !prefersReducedMotion();

  function stepSize() {
    const first = items[0];
    if (!first) return scroller.clientWidth * 0.8;
    const styles = window.getComputedStyle(track);
    const gap = parseFloat(styles.columnGap || styles.gap) || 16;
    return first.getBoundingClientRect().width + gap;
  }

  function updateButtons() {
    if (!prev || !next) return;
    const max = scroller.scrollWidth - scroller.clientWidth;
    prev.disabled = scroller.scrollLeft <= 2;
    next.disabled = scroller.scrollLeft >= max - 2;
  }

  function go(dir) {
    scroller.scrollBy({
      left: dir * stepSize(),
      behavior: smooth ? "smooth" : "auto",
    });
  }

  prev?.addEventListener("click", () => go(-1));
  next?.addEventListener("click", () => go(1));
  scroller.addEventListener("scroll", updateButtons, { passive: true });
  window.addEventListener("resize", updateButtons, { passive: true });
  updateButtons();
  window.setTimeout(updateButtons, 400);
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

function initProgramDetail() {
  if (document.querySelector("#pd-reviews")) {
    initTestimonialSlider("#pd-reviews");
  }
  document.querySelectorAll("[data-pd-slider]").forEach(initPdSlider);
  document.querySelectorAll("[data-pd-dots]").forEach(initScrollSpy);
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initProgramDetail);
} else {
  initProgramDetail();
}
