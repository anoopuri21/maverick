/**
 * Global reveal observer for .fade-up elements
 * Uses IntersectionObserver for performance
 */

import { respectsReducedMotion, isMobile } from "../shared/utils.js";

let observer = null;

/**
 * Initialize global fade-up observer
 */
export function initRevealObserver() {
  if (observer) return;

  if (respectsReducedMotion()) {
    // Skip animations for reduced motion
    document.querySelectorAll(".fade-up").forEach((el) => {
      el.style.opacity = "1";
      el.style.transform = "translateY(0)";
    });
    return;
  }

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          el.style.transition = "opacity 0.7s ease-out, transform 0.7s ease-out";
          el.style.opacity = "1";
          el.style.transform = "translateY(0)";
          observer.unobserve(el);
        }
      });
    },
    {
      rootMargin: "100px",
      threshold: 0.1,
    }
  );

  // Observe all fade-up elements
  document.querySelectorAll(".fade-up").forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = `translateY(${isMobile() ? "24px" : "40px"})`;
    observer.observe(el);
  });
}

/**
 * Disconnect observer
 */
export function disconnectRevealObserver() {
  if (observer) {
    observer.disconnect();
    observer = null;
  }
}

// Auto-initialize
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initRevealObserver);
} else {
  initRevealObserver();
}
