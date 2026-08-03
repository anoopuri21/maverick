/**
 * Counter animation for impact stats
 * Reads target from [data-counter-target] attribute and animates [data-counter] child
 */

import { respectsReducedMotion } from "../shared/utils.js";

/**
 * Animate counter from 0 to target
 * @param {HTMLElement} element - Element to animate
 * @param {number} target - Target number
 * @param {number} duration - Animation duration in seconds
 */
function animateCounter(element, target, duration) {
  const obj = { value: 0 };
  const originalText = element.textContent;

  gsap.to(obj, {
    value: target,
    duration: duration,
    ease: "power2.out",
    onUpdate() {
      element.textContent = Math.round(obj.value).toLocaleString("en-US");
    },
    onComplete() {
      element.textContent = originalText;
    },
  });
}

/**
 * Initialize counter animations for impact section
 * @param {string|HTMLElement} selector - CSS selector or DOM element (should be #impact)
 */
export function initCounterAnimation(selector) {
  const section = typeof selector === "string"
    ? document.querySelector(selector)
    : selector;

  if (!section) return;

  if (respectsReducedMotion()) return;

  const counterCards = section.querySelectorAll("[data-counter-target]");

  if (counterCards.length === 0) return;

  counterCards.forEach((card) => {
    const target = parseInt(card.getAttribute("data-counter-target"), 10);
    const counterEl = card.querySelector("[data-counter]");

    if (isNaN(target) || !counterEl) return;

    ScrollTrigger.create({
      trigger: card,
      start: "top 85%",
      once: true,
      onEnter: () => {
        const duration = target >= 1000 ? 2 : 1.5;
        animateCounter(counterEl, target, duration);
      },
    });
  });
}

/**
 * Default export for lazy loading
 */
export default function init(element) {
  initCounterAnimation(element);
}
