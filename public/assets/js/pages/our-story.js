/**
 * Our Story page orchestrator
 * Lazy-loads animation components based on existing DOM elements
 * Adapts to actual blade template structure without DOM changes
 */

import { lazyLoadComponent } from "../shared/utils.js";
import { initCore, cleanupAll } from "../core/animations-core.js";

// Track initialization
let isInitialized = false;

/**
 * Initialize Our Story animations
 */
function initOurStory() {
  if (isInitialized) return;
  if (!window.location.pathname.includes("our-story")) return;

  isInitialized = true;

  // Initialize core systems
  initCore();

  // Wait for core to be ready
  const initWhenReady = () => {
    // Section reveal — #journey REMOVED intentionally:
    // Desktop: timeline-pinned.js handles horizontal scroll
    // Mobile:  reveal-observer.js handles .fade-up on mobile cards
    // Adding #journey here would cause triple animation conflict
    const sections = [
      "#story-hero",
      "#beginning",
      "#today",
      "#impact",
      "#vision",
      "#ceo-message",
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

    // Hero parallax
    if (document.querySelector("#story-hero")) {
      lazyLoadComponent(
        "#story-hero",
        () => import("../components/hero-parallax.js"),
      );
    }

    // Counter animation - only for #impact section
    if (document.querySelector("#impact")) {
      lazyLoadComponent(
        "#impact",
        () => import("../components/counter-animation.js"),
      );
    }

    // Image slide-in - for beginning and today sections
    if (document.querySelector("#beginning")) {
      lazyLoadComponent(
        "#beginning",
        () => import("../components/image-slide-in.js"),
      );
    }
    if (document.querySelector("#today")) {
      lazyLoadComponent(
        "#today",
        () => import("../components/image-slide-in.js"),
      );
    }

    // Timeline pinned scroll
    // Note: lazyLoadComponent observes [data-journey-pin] (.os-journey__pin-wrap)
    // On mobile, CSS sets display:none on pin-wrap — IntersectionObserver
    // will NOT fire for hidden elements, so mobile cards are safe
    if (document.querySelector("[data-journey-pin]")) {
      lazyLoadComponent(
        "[data-journey-pin]",
        () => import("../components/timeline-pinned.js"),
      );
    }

    // Testimonials autoplay slider
    if (document.querySelector("#testimonials")) {
      lazyLoadComponent(
        "#testimonials",
        () => import("../components/testimonial-slider.js"),
      );
    }

    // Gallery collage + lightbox
    if (document.querySelector("#gallery")) {
      lazyLoadComponent(
        "#gallery",
        () => import("../components/gallery-collage.js"),
      );
    }

    // Footer animations
    if (document.querySelector("#footer")) {
      lazyLoadComponent(
        "#footer",
        () => import("../components/footer-animations.js"),
      );
    }
  };

  // Check if core is ready, otherwise wait for event
  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    initWhenReady();
  } else {
    document.addEventListener("animationsCoreReady", initWhenReady, {
      once: true,
    });
  }

  // Cleanup on page unload
  window.addEventListener("beforeunload", cleanupAll);
}

// Auto-initialize
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initOurStory);
} else {
  initOurStory();
}

// Fallback timeout
setTimeout(() => {
  if (!isInitialized && window.location.pathname.includes("our-story")) {
    console.warn("Our Story: Fallback initialization triggered");
    initOurStory();
  }
}, 1000);
