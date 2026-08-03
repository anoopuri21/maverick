/**
 * Timeline Pinned — Horizontal Scroll (Desktop Only)
 *
 * Desktop (≥1024px): GSAP ScrollTrigger horizontal pin
 * Mobile (<1024px):  CSS + reveal-observer.js handles .fade-up cards
 *
 * Receives: DOM element ([data-journey-pin] = .os-journey__pin-wrap)
 * Called by: lazyLoadComponent in pages/our-story.js
 */

import { respectsReducedMotion } from "../shared/utils.js";

// Module-level context — one instance only
let ctx = null;

/**
 * Initialize timeline pinned horizontal scroll
 * @param {HTMLElement} element - The [data-journey-pin] DOM element
 */
export function initTimelinePinned(element) {
  // ── 1. Element validation ──────────────────────────────────────────
  const pinEl =
    element instanceof HTMLElement
      ? element
      : document.querySelector("[data-journey-pin]");

  if (!pinEl) return;

  // Scope all queries to the section wrapper (.os-journey)
  const section = pinEl.closest(".os-journey") ?? pinEl.parentElement;
  const track = pinEl.querySelector("[data-journey-track]");
  const slides = pinEl.querySelectorAll("[data-journey-slide]");
  const hint = pinEl.querySelector("[data-journey-hint]");

  // ── 2. Guard: required elements ───────────────────────────────────
  if (!track || slides.length === 0) return;

  // ── 3. Cleanup any previous context ──────────────────────────────
  cleanup();

  // ── 4. Reduced motion — skip all animation ────────────────────────
  if (respectsReducedMotion()) {
    return;
  }

  // ── 6. gsap.matchMedia context ────────────────────────────────────
  ctx = gsap.matchMedia();

  ctx.add("(min-width: 1024px)", () => {
    // ── DESKTOP: Horizontal pinned scroll ──────────────────────────
    let hintFaded = false;
    const slideCount = slides.length;

    // Single tween with inline scrollTrigger config
    const tween = gsap.to(track, {
      x: () => -(track.scrollWidth - window.innerWidth),
      ease: "none",
      scrollTrigger: {
        trigger: pinEl,
        start: "top top",
        end: () => "+=" + (track.scrollWidth - window.innerWidth),
        pin: true,
        scrub: 1,
        anticipatePin: 1,
        invalidateOnRefresh: true,
        onUpdate: (self) => {
          // Hint fade
          if (!hintFaded && hint && self.progress > 0.05) {
            hintFaded = true;
            gsap.to(hint, {
              opacity: 0,
              duration: 0.4,
              ease: "power2.out",
            });
          }
        },
      },
    });

    // gsap.matchMedia cleanup
    return () => {
      tween.scrollTrigger?.kill();
      tween.kill();
    };
  });

  ctx.add("(max-width: 1023px)", () => {
    // ── MOBILE: No JS animation ────────────────────────────────────
    // CSS shows .os-journey__mobile, hides .os-journey__pin-wrap
    // reveal-observer.js handles .fade-up on .os-journey__mobile-card
    return () => {};
  });
}

/**
 * Cleanup all GSAP context
 */
export function cleanup() {
  if (ctx) {
    ctx.revert();
    ctx = null;
  }
}

/**
 * Default export for lazy loading via lazyLoadComponent
 */
export default function init(element) {
  initTimelinePinned(element);
}
