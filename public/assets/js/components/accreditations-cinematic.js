/**
 * Accreditations Cinematic — Full-screen Pinned Image Section
 *
 * Desktop (≥1024px): 
 * - Image starts centered (60vw x 60vh)
 * - On scroll: scales to 100vw x 100vh, pins to viewport
 * - Text appears in center
 * - Releases into next section
 *
 * Mobile (<1024px): Static fallback (handled by CSS)
 *
 * Receives: DOM element ([data-cinematic-pin])
 */

import { respectsReducedMotion } from "../shared/utils.js";

// Module-level context
let ctx = null;

/**
 * Initialize Accreditations Cinematic animation
 * @param {HTMLElement} element - The [data-cinematic-pin] DOM element
 */
export function initAccredCinematic(element) {
  const pinEl = element instanceof HTMLElement ? element : document.querySelector("[data-cinematic-pin]");
  if (!pinEl) return;

  const bg = pinEl.querySelector(".accred-cinematic__bg");
  const image = pinEl.querySelector("[data-cinematic-image]");
  const content = pinEl.querySelector("[data-cinematic-content]");
  const overlay = pinEl.querySelector(".accred-cinematic__overlay");

  if (!bg || !image || !content) return;

  // Cleanup
  cleanup();

  // Reduced motion
  if (respectsReducedMotion()) return;

  ctx = gsap.matchMedia();

  ctx.add("(min-width: 1024px)", () => {
    // ── 1. Set initial states ──────────────────────────
    gsap.set(bg, {
      width: "60vw",
      height: "60vh",
      borderRadius: "20px"
    });
    gsap.set(image, { scale: 1.2 });
    gsap.set(overlay, { opacity: 0 });
    gsap.set(content, { opacity: 0, y: 50 });

    // ── 2. Timeline ──────────────────────────────────
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: pinEl,
        start: "top top",
        end: "+=150%",
        pin: true,
        scrub: 1,
        anticipatePin: 1,
        invalidateOnRefresh: true,
      }
    });

    tl.to(bg, {
      width: "100vw",
      height: "100vh",
      borderRadius: "0px",
      ease: "none"
    }, 0)
    .to(image, {
      scale: 1,
      ease: "none"
    }, 0)
    .to(overlay, {
      opacity: 1,
      ease: "none"
    }, 0.2)
    .to(content, {
      opacity: 1,
      y: 0,
      ease: "power2.out"
    }, 0.4);

    return () => {
      tl.scrollTrigger?.kill();
      tl.kill();
    };
  });
}

/**
 * Cleanup
 */
export function cleanup() {
  if (ctx) {
    ctx.revert();
    ctx = null;
  }
}

/**
 * Default export
 */
export default function init(element) {
  initAccredCinematic(element);
}
