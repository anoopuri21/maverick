/**
 * CSR & Community Impact Page Animations
 * Powered by GSAP, ScrollTrigger, and Lenis (preloaded)
 */

document.addEventListener('DOMContentLoaded', () => {
  // Ensure GSAP & ScrollTrigger are loaded; otherwise fall back to CSS default styles
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    console.warn('GSAP or ScrollTrigger not loaded. Falling back to CSS transitions.');
    return;
  }

  // Register the ScrollTrigger plugin with GSAP
  gsap.registerPlugin(ScrollTrigger);

  // Initialize all page scroll & entrance animations
  initHeroParallax();
  initEntranceReveals();
  initImpactCounters();
});

/**
 * 1. Page Banner Hero Parallax
 */
function initHeroParallax() {
  gsap.to('.csr-hero__bg', {
    scrollTrigger: {
      trigger: '.csr-hero',
      start: 'top top',
      end: 'bottom top',
      scrub: true
    },
    yPercent: 12,
    ease: 'none'
  });

  // Entrance timeline for hero titles and taglines
  const heroTimeline = gsap.timeline();
  heroTimeline.fromTo('.csr-hero__title',
    { opacity: 0, y: 40 },
    { opacity: 1, y: 0, duration: 1.0, ease: 'power3.out' }
  );
  heroTimeline.fromTo('.csr-hero__tagline',
    { opacity: 0, y: 25 },
    { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out' },
    '-=0.7'
  );
}

/**
 * 2. Section Staggered Entrance Animations
 */
function initEntranceReveals() {
  // Section 1: Our Commitment Contents
  gsap.from('.csr-commitment__content > *', {
    scrollTrigger: {
      trigger: '.csr-commitment',
      start: 'top 85%',
      toggleActions: 'play none none none'
    },
    opacity: 0,
    y: 35,
    duration: 0.8,
    stagger: 0.15,
    ease: 'power3.out'
  });

  gsap.from('.csr-commitment__visual', {
    scrollTrigger: {
      trigger: '.csr-commitment',
      start: 'top 80%',
      toggleActions: 'play none none none'
    },
    opacity: 0,
    scale: 0.96,
    duration: 1.0,
    ease: 'power3.out'
  });

  // Section 2: CSR Focus Areas Cards
  gsap.from('.csr-focus-card', {
    scrollTrigger: {
      trigger: '.csr-focus',
      start: 'top 80%',
      toggleActions: 'play none none none'
    },
    opacity: 0,
    y: 40,
    duration: 0.8,
    stagger: 0.12,
    ease: 'power3.out'
  });

  // Section 3: CSR Activities Gallery Cards
  gsap.from('.csr-gallery-card', {
    scrollTrigger: {
      trigger: '.csr-gallery',
      start: 'top 80%',
      toggleActions: 'play none none none'
    },
    opacity: 0,
    y: 45,
    scale: 0.98,
    duration: 0.9,
    stagger: 0.18,
    ease: 'power3.out'
  });

  // Section 5: Scholarship & Educational Support Checklist
  gsap.from('.csr-scholarship__intro > *', {
    scrollTrigger: {
      trigger: '.csr-scholarship',
      start: 'top 85%',
      toggleActions: 'play none none none'
    },
    opacity: 0,
    y: 30,
    duration: 0.8,
    stagger: 0.15,
    ease: 'power3.out'
  });

  gsap.from('.csr-checklist-card', {
    scrollTrigger: {
      trigger: '.csr-scholarship',
      start: 'top 80%',
      toggleActions: 'play none none none'
    },
    opacity: 0,
    y: 25,
    duration: 0.6,
    stagger: 0.08,
    ease: 'power3.out'
  });
}

/**
 * 3. Count-up Numbers Animation
 */
function initImpactCounters() {
  const countElements = document.querySelectorAll('.csr-impact-card__number');

  countElements.forEach(element => {
    const targetValue = parseInt(element.getAttribute('data-target'), 10) || 0;

    gsap.fromTo(element,
      { textContent: 0 },
      {
        scrollTrigger: {
          trigger: '.csr-impact',
          start: 'top 85%',
          toggleActions: 'play none none none'
        },
        textContent: targetValue,
        duration: 1.8,
        ease: 'power2.out',
        snap: { textContent: 1 },
        onUpdate: function () {
          // Round decimal points to integers perfectly
          element.innerHTML = Math.ceil(parseFloat(element.textContent));
        }
      }
    );
  });
}
