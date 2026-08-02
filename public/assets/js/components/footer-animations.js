/**
 * Footer animations
 * Handles year update, newsletter form, and column fade-up
 */

import { respectsReducedMotion } from "../shared/utils.js";

/**
 * Initialize footer animations
 * @param {string|HTMLElement} selector - CSS selector or DOM element (should be #footer)
 */
export function initFooterAnimations(selector) {
  const footer = typeof selector === "string" 
    ? document.querySelector(selector) 
    : selector;
  
  if (!footer) return;

  // Update current year
  const yearEl = footer.querySelector("[data-current-year]");
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // Newsletter form handling
  const form = footer.querySelector("[data-newsletter-form]");
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const input = form.querySelector(".footer__newsletter-input");
      const btn = form.querySelector(".footer__newsletter-btn span");
      if (input && input.value && btn) {
        const orig = btn.textContent;
        btn.textContent = "Subscribed";
        input.value = "";
        setTimeout(() => {
          btn.textContent = orig;
        }, 2500);
      }
    });
  }

  if (respectsReducedMotion()) return;

  // Column fade-up animation
  const cols = footer.querySelectorAll(".footer__col");
  if (cols.length) {
    gsap.set(cols, { opacity: 0, y: 30 });
    gsap.to(cols, {
      opacity: 1,
      y: 0,
      duration: 0.7,
      stagger: 0.12,
      ease: "power2.out",
      scrollTrigger: { 
        trigger: footer, 
        start: "top 85%", 
        once: true 
      },
    });
  }
}

/**
 * Default export for lazy loading
 */
export default function init(element) {
  initFooterAnimations(element);
}
