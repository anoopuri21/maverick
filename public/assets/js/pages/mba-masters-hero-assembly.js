/**
 * Hero + Enquiry — cinematic page-load assembly.
 * This module is intentionally scoped to [data-hero-assembly] only.
 */
(function () {
  "use strict";

  var hero = document.querySelector("[data-hero-assembly]");
  if (!hero) return;

  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var image = hero.querySelector("[data-hero-image]");
  var wash = hero.querySelector("[data-hero-wash]");
  var registration = hero.querySelector("[data-hero-registration]");
  var masthead = hero.querySelector("[data-hero-masthead]");
  var statement = hero.querySelector("[data-hero-statement]");
  var titleLines = hero.querySelectorAll("[data-hero-title-line]");
  var enquiry = hero.querySelector("[data-hero-enquiry]");
  var folio = hero.querySelector("[data-hero-folio]");

  function showFinalState() {
    hero.classList.add("is-hero-assembled");
    [image, wash, registration, masthead, statement, enquiry, folio].forEach(function (element) {
      if (element) element.removeAttribute("data-hero-pending");
    });
    titleLines.forEach(function (line) {
      line.removeAttribute("data-hero-pending");
    });
  }

  function runCssFallback() {
    showFinalState();
  }

  if (reduced || typeof window.gsap === "undefined") {
    runCssFallback();
    return;
  }

  var tl = window.gsap.timeline({
    defaults: { ease: "power3.out" },
    onComplete: showFinalState,
  });

  window.gsap.set([image, wash], { opacity: 0 });
  window.gsap.set(image, { scale: 1.08, filter: "saturate(0.35) contrast(1.05) brightness(0.42) blur(4px)" });
  window.gsap.set(registration, { opacity: 0, clipPath: "inset(0 100% 0 0)" });
  window.gsap.set(masthead ? masthead.children : [], { opacity: 0, y: -14 });
  window.gsap.set(statement ? statement.children : [], { opacity: 0, y: 22 });
  window.gsap.set(titleLines, { opacity: 0, yPercent: 115 });
  window.gsap.set(enquiry, { opacity: 0, x: 42, clipPath: "inset(0 0 0 100%)" });
  window.gsap.set(folio ? folio.children : [], { opacity: 0, y: 12 });

  tl.to([image, wash], { opacity: 1, duration: 0.42 }, 0)
    .to(image, { scale: 1.035, filter: "saturate(0.68) contrast(1.08) brightness(0.64) blur(0px)", duration: 1.05 }, 0)
    .to(registration, { opacity: 1, clipPath: "inset(0 0% 0 0)", duration: 0.72 }, 0.16)
    .to(masthead ? masthead.children : [], { opacity: 1, y: 0, duration: 0.55, stagger: 0.06 }, 0.34)
    .to(statement ? statement.children : [], { opacity: 1, y: 0, duration: 0.62, stagger: 0.07 }, 0.58)
    .to(titleLines, { opacity: 1, yPercent: 0, duration: 0.82, stagger: 0.11 }, 0.72)
    .to(enquiry, { opacity: 1, x: 0, clipPath: "inset(0 0% 0 0)", duration: 0.9 }, 1.03)
    .to(folio ? folio.children : [], { opacity: 1, y: 0, duration: 0.5, stagger: 0.06 }, 1.42);
})();
