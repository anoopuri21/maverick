/**
 * Programme detail page orchestrator
 * Initialises shared components (e.g. the Our Story testimonials slider)
 * directly once the DOM is ready. The slider is the SAME component used on
 * the our-story page — reused here with a different heading.
 */

import { initTestimonialSlider } from "../components/testimonial-slider.js";

function initProgramDetail() {
  if (document.querySelector("#pd-reviews")) {
    initTestimonialSlider("#pd-reviews");
  }
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initProgramDetail);
} else {
  initProgramDetail();
}
