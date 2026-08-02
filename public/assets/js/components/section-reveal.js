/**
 * Generic section reveal animation
 * Handles .fade-up and .text-reveal-inner animations for sections
 */

import { respectsReducedMotion, isMobile } from "../shared/utils.js";

/**
 * Initialize reveal animations for a section
 * @param {string|HTMLElement} selector - CSS selector or DOM element
 */
export function initSectionReveal(selector) {
  const section = typeof selector === "string" 
    ? document.querySelector(selector) 
    : selector;
  
  if (!section) return;

  const textInners = section.querySelectorAll(".text-reveal-inner");
  const fadeUps = section.querySelectorAll(".fade-up");

  if (respectsReducedMotion()) {
    gsap.set(textInners, { y: "0%", clearProps: "transform" });
    gsap.set(fadeUps, { opacity: 1, y: 0, clearProps: "transform" });
    return;
  }

  if (textInners.length) {
    gsap.set(textInners, { y: "110%" });
    gsap.to(textInners, {
      y: "0%",
      duration: 0.9,
      stagger: 0.12,
      ease: "power3.out",
      scrollTrigger: { 
        trigger: section, 
        start: "top 78%", 
        once: true 
      },
    });
  }

  if (fadeUps.length) {
    gsap.set(fadeUps, { opacity: 0, y: isMobile() ? 24 : 40 });
    gsap.to(fadeUps, {
      opacity: 1,
      y: 0,
      duration: 0.7,
      stagger: 0.1,
      ease: "power2.out",
      scrollTrigger: { 
        trigger: section, 
        start: "top 78%", 
        once: true 
      },
    });
  }
}

/**
 * Default export for lazy loading
 */
export default function init(element) {
  initSectionReveal(element);
}
