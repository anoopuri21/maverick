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
  if (form && form.dataset.newsletterBound !== "1") {
    form.dataset.newsletterBound = "1";
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const input = form.querySelector(".footer__newsletter-input");
      const btn = form.querySelector(".footer__newsletter-btn span");
      if (!input || !input.value || !btn) return;
      const orig = btn.textContent;
      const resetDelay = 2500;
      try {
        const res = await fetch(form.action, {
          method: "POST",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: new FormData(form),
        });
        let data = {};
        try {
          data = await res.json();
        } catch (_) {
          /* non-JSON response */
        }

        if (res.ok && data.ok) {
          // Swap the form for an inline thank-you note that stays visible.
          const note = document.createElement("p");
          note.className = "footer__newsletter-thanks";
          note.setAttribute("role", "status");

          const icon = document.createElement("span");
          icon.className = "footer__newsletter-thanks-icon";
          icon.setAttribute("aria-hidden", "true");
          icon.innerHTML =
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';

          const text = document.createElement("span");
          text.textContent = data.message || "Thank you for subscribing.";

          note.append(icon, text);
          form.replaceWith(note);
          return;
        } else if (res.status === 422) {
          btn.textContent = data.errors?.email?.[0] || "Invalid email";
        } else if (res.status === 429) {
          btn.textContent = "Too many tries. Wait a moment.";
        } else {
          btn.textContent = data.message || "Something went wrong";
        }
      } catch (_) {
        btn.textContent = "Network error";
      }
      setTimeout(() => {
        btn.textContent = orig;
      }, resetDelay);
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
