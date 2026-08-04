/**
 * Accreditations page orchestrator
 * Lazy-loads animation components based on existing DOM elements
 */

import { lazyLoadComponent } from "../shared/utils.js";
import { initCore, cleanupAll } from "../core/animations-core.js";

// Track initialization
let isInitialized = false;

/**
 * Initialize Accreditations animations
 */
function initAccreditations() {
  if (isInitialized) return;
  if (!window.location.pathname.includes("accreditations")) return;

  isInitialized = true;

  // Initialize core systems
  initCore();

  const initWhenReady = () => {
    // Cinematic Section
    if (document.querySelector("[data-cinematic-pin]")) {
      lazyLoadComponent(
        "[data-cinematic-pin]",
        () => import("../components/accreditations-cinematic.js"),
      );
    }

    // You can add other components here if needed, 
    // like section-reveal for other sections
    const sections = [
      ".accred-hero",
      ".accreditations",
      ".awards",
      ".quality",
      ".media-rankings",
      "#final-cta",
    ];

    sections.forEach((selector) => {
      if (document.querySelector(selector)) {
        lazyLoadComponent(
          selector,
          () => import("../components/section-reveal.js"),
        );
      }
    });
  };

  // Wait for GSAP core
  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    initWhenReady();
  } else {
    document.addEventListener("animationsCoreReady", initWhenReady, {
      once: true,
    });
  }

  // Cleanup
  window.addEventListener("beforeunload", cleanupAll);
}

// Auto-initialize
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initAccreditations);
} else {
  initAccreditations();
}
