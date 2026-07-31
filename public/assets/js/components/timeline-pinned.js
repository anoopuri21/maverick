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

  if (!pinEl) {
    console.warn("[timeline-pinned] pin element not found — aborting");
    return;
  }

  // Scope all queries to the section wrapper (.os-journey)
  const section = pinEl.closest(".os-journey") ?? pinEl.parentElement;
  const track = pinEl.querySelector("[data-journey-track]");
  const slides = pinEl.querySelectorAll("[data-journey-slide]");
  const hint = pinEl.querySelector("[data-journey-hint]");

  console.log("[timeline-pinned] initializing...");
  console.log("[timeline-pinned] pin element:", pinEl);
  console.log("[timeline-pinned] track:", track);
  console.log("[timeline-pinned] slides:", slides.length);

  // ── 2. Guard: required elements ───────────────────────────────────
  if (!track) {
    console.warn("[timeline-pinned] [data-journey-track] not found — aborting");
    return;
  }

  if (slides.length === 0) {
    console.warn(
      "[timeline-pinned] no [data-journey-slide] elements found — aborting",
    );
    return;
  }

  // ── 3. Cleanup any previous context ──────────────────────────────
  cleanup();

  // ── 4. Reduced motion — skip all animation ────────────────────────
  if (respectsReducedMotion()) {
    console.log(
      "[timeline-pinned] reduced motion detected — skipping animation",
    );
    return;
  }

  // ── 5. gsap.matchMedia context ────────────────────────────────────
  ctx = gsap.matchMedia();

  ctx.add("(min-width: 1024px)", () => {
    // ── DESKTOP: Horizontal pinned scroll ──────────────────────────

    console.log("[timeline-pinned] mode: desktop-pin");

    // Hint fade flag — fire only once
    let hintFaded = false;

    // Single tween with inline scrollTrigger config (correct GSAP pattern)
    const tween = gsap.to(track, {
      x: () => -(track.scrollWidth - window.innerWidth),
      ease: "none",
      scrollTrigger: {
        trigger: pinEl,
        start: "top top",
        // end is a function so invalidateOnRefresh recalculates correctly
        end: () => "+=" + (track.scrollWidth - window.innerWidth),
        pin: true,
        scrub: 1,
        anticipatePin: 1,
        invalidateOnRefresh: true,
        onUpdate: (self) => {
          // Hint fade — ek baar only, flag se guard
          if (!hintFaded && hint && self.progress > 0.05) {
            hintFaded = true;
            gsap.to(hint, {
              opacity: 0,
              duration: 0.4,
              ease: "power2.out",
            });
          }
        },
        onEnter: () => {
          console.log("[timeline-pinned] pin entered");
        },
        onLeave: () => {
          console.log("[timeline-pinned] pin exited");
        },
      },
    });

    console.log(
      "[timeline-pinned] scroll distance:",
      track.scrollWidth - window.innerWidth + "px",
    );
    console.log("[timeline-pinned] done ✓");

    // gsap.matchMedia cleanup — runs when breakpoint exits
    return () => {
      tween.scrollTrigger?.kill();
      tween.kill();
      console.log("[timeline-pinned] desktop context cleaned up");
    };
  });

  ctx.add("(max-width: 1023px)", () => {
    // ── MOBILE: No JS animation ────────────────────────────────────
    // CSS shows .os-journey__mobile, hides .os-journey__pin-wrap
    // reveal-observer.js handles .fade-up on .os-journey__mobile-card
    console.log(
      "[timeline-pinned] mode: mobile — CSS + reveal-observer active",
    );

    // No cleanup needed — nothing created
    return () => {};
  });
}

/**
 * Cleanup all GSAP context — kills tween + ScrollTrigger + matchMedia
 */
export function cleanup() {
  if (ctx) {
    ctx.revert();
    ctx = null;
    console.log("[timeline-pinned] context reverted");
  }
}

/**
 * Default export for lazy loading via lazyLoadComponent
 * Receives DOM element from lazyLoadComponent (entry.target)
 */
export default function init(element) {
  initTimelinePinned(element);
}
