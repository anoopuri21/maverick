/**
 * Image slide-in animations for beginning and today sections
 * Adapted to use .beginning__image-wrapper and .today__image-wrapper
 */

import { respectsReducedMotion } from "../shared/utils.js";

/**
 * Initialize image slide-in for beginning section
 * @param {string|HTMLElement} selector - CSS selector or DOM element (should be #beginning)
 */
export function initBeginningImageSlide(selector) {
  const section = typeof selector === "string" 
    ? document.querySelector(selector) 
    : selector;
  
  if (!section) return;

  if (respectsReducedMotion()) return;

  const img = section.querySelector(".beginning__image-wrapper");
  if (img) {
    gsap.fromTo(
      img,
      { x: 60, opacity: 0 },
      {
        x: 0,
        opacity: 1,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
          trigger: section,
          start: "top 70%",
          once: true,
        },
      }
    );
  }
}

/**
 * Initialize image slide-in for today section
 * @param {string|HTMLElement} selector - CSS selector or DOM element (should be #today)
 */
export function initTodayImageSlide(selector) {
  const section = typeof selector === "string" 
    ? document.querySelector(selector) 
    : selector;
  
  if (!section) return;

  if (respectsReducedMotion()) return;

  const img = section.querySelector(".today__image-wrapper");
  if (img) {
    gsap.fromTo(
      img,
      { x: -60, opacity: 0 },
      {
        x: 0,
        opacity: 1,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: { 
          trigger: section, 
          start: "top 70%", 
          once: true 
        },
      }
    );
  }
}

/**
 * Default export for lazy loading
 * Detects which section based on element ID
 */
export default function init(element) {
  if (element.id === "beginning") {
    initBeginningImageSlide(element);
  } else if (element.id === "today") {
    initTodayImageSlide(element);
  }
}
