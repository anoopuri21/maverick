/**
 * Shared utility functions for animation components
 */

/**
 * Check if user prefers reduced motion
 * @returns {boolean}
 */
export function respectsReducedMotion() {
  return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}

/**
 * Check if device is mobile (width < 1024px)
 * @returns {boolean}
 */
export function isMobile() {
  return window.innerWidth < 1024;
}

/**
 * Execute callback when element enters viewport
 * @param {string} selector - CSS selector for target element
 * @param {Function} callback - Function to execute when visible
 * @param {Object} options - IntersectionObserver options
 */
export function onVisible(selector, callback, options = {}) {
  const element = document.querySelector(selector);
  if (!element) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          callback(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    {
      rootMargin: "200px",
      threshold: 0.1,
      ...options,
    }
  );

  observer.observe(element);
}

/**
 * Lazy load a component module when element approaches viewport
 * @param {string} selector - CSS selector for target element
 * @param {Function} importFn - Dynamic import function
 * @param {Object} options - IntersectionObserver options
 */
export async function lazyLoadComponent(selector, importFn, options = {}) {
  const element = document.querySelector(selector);
  if (!element) return;

  const observer = new IntersectionObserver(
    async (entries) => {
      entries.forEach(async (entry) => {
        if (entry.isIntersecting) {
          observer.unobserve(entry.target);
          try {
            const module = await importFn();
            if (module.default) {
              module.default(entry.target);
            } else if (module.init) {
              module.init(entry.target);
            }
          } catch (error) {
            console.error(`Failed to load component for ${selector}:`, error);
          }
        }
      });
    },
    {
      rootMargin: "400px",
      threshold: 0.01,
      ...options,
    }
  );

  observer.observe(element);
}
