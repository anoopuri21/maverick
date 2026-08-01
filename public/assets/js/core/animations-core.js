/**
 * Core animation initialization
 * Handles GSAP, ScrollTrigger registration, and Lenis smooth scroll integration
 */

import { respectsReducedMotion } from "../shared/utils.js";

let lenis = null;
let isInitialized = false;

/**
 * Initialize GSAP and ScrollTrigger
 */
export function initGSAP() {
  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
    console.warn("GSAP or ScrollTrigger not loaded");
    return false;
  }

  gsap.registerPlugin(ScrollTrigger);
  return true;
}

/**
 * Initialize Lenis smooth scroll
 */
export function initLenis() {
  if (typeof Lenis === "undefined") {
    console.warn("Lenis not loaded");
    return null;
  }

  if (respectsReducedMotion()) {
    console.log("Reduced motion enabled - skipping Lenis");
    return null;
  }

  lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    orientation: "vertical",
    gestureOrientation: "vertical",
    smoothWheel: true,
    wheelMultiplier: 1,
    touchMultiplier: 2,
    infinite: false,
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }

  requestAnimationFrame(raf);

  // Sync Lenis with ScrollTrigger
  lenis.on("scroll", ScrollTrigger.update);

  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });

  gsap.ticker.lagSmoothing(0);

  return lenis;
}

/**
 * Initialize all core animation systems
 */
export function initCore() {
  if (isInitialized) return;

  const gsapReady = initGSAP();
  if (!gsapReady) return;

  lenis = initLenis();
  isInitialized = true;

  // Dispatch event for other modules to listen
  document.dispatchEvent(new CustomEvent("animationsCoreReady"));

  // Set global flag for legacy compatibility
  window.__lenisReady = true;
  window.__animationsStarted = true;
}

/**
 * Cleanup all ScrollTrigger instances
 */
export function cleanupAll() {
  ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
}

/**
 * Get Lenis instance
 */
export function getLenis() {
  return lenis;
}

/**
 * Check if core is initialized
 */
export function isCoreInitialized() {
  return isInitialized;
}

// Auto-initialize if on our-story page
if (window.location.pathname.includes("our-story")) {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCore);
  } else {
    initCore();
  }
}
