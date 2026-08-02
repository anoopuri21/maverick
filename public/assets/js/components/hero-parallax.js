/**
 * Hero parallax animations
 * Subtle parallax on .os-hero__shape elements with different speeds
 */

import { respectsReducedMotion, isMobile } from "../shared/utils.js";

let triggers = [];

/**
 * Initialize hero parallax animations
 * @param {string|HTMLElement} selector - CSS selector or DOM element (should be #story-hero)
 */
export function initHeroParallax(selector) {
  const hero = typeof selector === "string" 
    ? document.querySelector(selector) 
    : selector;
  
  if (!hero) return;

  const shapes = hero.querySelectorAll(".os-hero__shape");
  console.log('[hero-parallax] shapes:', shapes.length);

  if (respectsReducedMotion() || isMobile()) {
    // Skip parallax on reduced motion or mobile for performance
    return;
  }

  // Cleanup existing triggers
  cleanup();

  // Apply parallax to each shape with different speeds
  shapes.forEach((shape, i) => {
    const speed = (i + 1) * -40; // Different speeds per shape
    
    const trigger = ScrollTrigger.create({
      trigger: hero,
      start: "top top",
      end: "bottom top",
      scrub: 1.5,
      onUpdate: (self) => {
        gsap.to(shape, {
          y: speed * self.progress,
          duration: 0.1,
          ease: "none",
        });
      },
    });
    
    triggers.push(trigger);
  });
}

/**
 * Cleanup ScrollTrigger instances
 */
export function cleanup() {
  triggers.forEach((trigger) => trigger.kill());
  triggers = [];
}

/**
 * Default export for lazy loading
 */
export default function init(element) {
  initHeroParallax(element);
}