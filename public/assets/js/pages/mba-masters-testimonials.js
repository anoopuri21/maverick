/**
 * Legacy compatibility shim — delegates directly to the shared standalone
 * `voices-testimonials.js` controller when loaded on any page.
 */
(function () {
  "use strict";

  if (window.MaverickVoicesTestimonials && typeof window.MaverickVoicesTestimonials.init === "function") {
    window.MaverickVoicesTestimonials.init();
  }
})();
