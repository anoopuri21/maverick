/**
 * Our Story testimonials autoplay slider
 * GSAP-driven, one-card steps, infinite loop, hover pause
 */

import { respectsReducedMotion } from "../shared/utils.js";

const GAP = 24;
const INTERVAL_MS = 3000;
const TWEEN_DURATION = 3;
const LOG = "[testimonial-slider]";

function getVisibleCount() {
  const w = window.innerWidth;
  if (w <= 768) return 1;
  if (w <= 1024) return 2;
  return 3;
}

/**
 * @param {string|HTMLElement} selector
 */
export function initTestimonialSlider(selector) {
  const section =
    typeof selector === "string" ? document.querySelector(selector) : selector;

  if (!section || section.dataset.sliderInit === "true") return;
  section.dataset.sliderInit = "true";

  const slider = section.querySelector("[data-testimonials-slider]");
  const viewport = section.querySelector(".os-testimonials__viewport");
  const track = section.querySelector(".os-testimonials__track");

  if (!slider || !viewport || !track) {
    console.warn(LOG, "Missing slider markup");
    return;
  }

  if (respectsReducedMotion()) {
    console.log(LOG, "Reduced motion — static layout, no autoplay");
    section.classList.add("os-testimonials--static");
    return;
  }

  if (typeof gsap === "undefined") {
    console.warn(LOG, "GSAP not available");
    return;
  }

  let originalCards = Array.from(
    track.querySelectorAll(".os-testimonials__card:not([data-clone])"),
  );
  let index = 0;
  let step = 0;
  let timer = null;
  let tween = null;
  let paused = false;
  let destroyed = false;
  let resizeTimer = null;

  console.log(LOG, "Init", { cards: originalCards.length });

  function clearClones() {
    track.querySelectorAll("[data-clone]").forEach((el) => el.remove());
  }

  function cloneForLoop() {
    clearClones();
    originalCards.forEach((card) => {
      const clone = card.cloneNode(true);
      clone.setAttribute("data-clone", "true");
      clone.setAttribute("aria-hidden", "true");
      track.appendChild(clone);
    });
    if (window.lucide?.createIcons) {
      window.lucide.createIcons();
    }
  }

  function layout() {
    originalCards = Array.from(
      track.querySelectorAll(".os-testimonials__card:not([data-clone])"),
    );
    if (!originalCards.length) return;

    const visible = getVisibleCount();
    const vw = viewport.clientWidth;
    const cardW = (vw - GAP * (visible - 1)) / visible;

    originalCards.forEach((card) => {
      card.style.flex = `0 0 ${cardW}px`;
      card.style.width = `${cardW}px`;
    });

    cloneForLoop();

    track.querySelectorAll("[data-clone]").forEach((card) => {
      card.style.flex = `0 0 ${cardW}px`;
      card.style.width = `${cardW}px`;
    });

    step = cardW + GAP;
    index = 0;
    if (tween) {
      tween.kill();
      tween = null;
    }
    gsap.set(track, { x: 0 });
    console.log(LOG, "Layout", { visible, cardW: Math.round(cardW), step: Math.round(step) });
  }

  function goNext() {
    if (destroyed || paused || !originalCards.length) return;
    if (tween && tween.isActive()) return;

    index += 1;
    tween = gsap.to(track, {
      x: -(index * step),
      duration: TWEEN_DURATION,
      ease: "power2.inOut",
      onComplete() {
        if (index >= originalCards.length) {
          index = 0;
          gsap.set(track, { x: 0 });
        }
      },
    });
  }

  function startAutoplay() {
    stopAutoplay();
    timer = window.setInterval(goNext, INTERVAL_MS);
    console.log(LOG, "Autoplay started");
  }

  function stopAutoplay() {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  }

  function onEnter() {
    paused = true;
    stopAutoplay();
    console.log(LOG, "Paused (hover)");
  }

  function onLeave() {
    paused = false;
    startAutoplay();
    console.log(LOG, "Resumed (mouseleave)");
  }

  function onResize() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (destroyed) return;
      console.log(LOG, "Resize — rebuild");
      stopAutoplay();
      layout();
      if (!paused) startAutoplay();
    }, 150);
  }

  function cleanup() {
    destroyed = true;
    stopAutoplay();
    if (tween) tween.kill();
    clearTimeout(resizeTimer);
    slider.removeEventListener("mouseenter", onEnter);
    slider.removeEventListener("mouseleave", onLeave);
    window.removeEventListener("resize", onResize);
    clearClones();
    gsap.set(track, { clearProps: "transform" });
    originalCards.forEach((card) => {
      card.style.flex = "";
      card.style.width = "";
    });
    delete section.dataset.sliderInit;
    console.log(LOG, "Cleanup");
  }

  layout();
  startAutoplay();

  slider.addEventListener("mouseenter", onEnter);
  slider.addEventListener("mouseleave", onLeave);
  window.addEventListener("resize", onResize);
  window.addEventListener("beforeunload", cleanup, { once: true });

  return cleanup;
}

export default function init(element) {
  return initTestimonialSlider(element);
}
