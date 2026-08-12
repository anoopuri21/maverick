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

function initProgramDetail() {
  if (document.querySelector("#pd-reviews")) {
    initTestimonialSlider("#pd-reviews");
  }
  document.querySelectorAll("[data-pd-slider]").forEach(initPdSlider);
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initProgramDetail);
} else {
  initProgramDetail();
}
