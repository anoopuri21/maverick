/* =========================================================
   DUAL MBA PROGRAMME — Animations & Interactions
   GSAP + ScrollTrigger powered
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
  // Ensure GSAP is loaded
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  gsap.registerPlugin(ScrollTrigger);

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // =============================================
  // HERO — Staggered Entrance
  // =============================================
  if (!prefersReducedMotion) {
    const heroTl = gsap.timeline({ delay: 0.3 });
    heroTl
      .from('[data-dmba-hero="tag"]', { opacity: 0, x: -20, duration: 0.6, ease: 'power3.out' })
      .from('[data-dmba-hero="headline"]', { opacity: 0, y: 30, duration: 0.7, ease: 'power3.out' }, '-=0.3')
      .from('[data-dmba-hero="sub"]', { opacity: 0, y: 20, duration: 0.6, ease: 'power3.out' }, '-=0.3')
      .from('[data-dmba-hero="stats"] .dmba-hero__stat', { opacity: 0, y: 15, duration: 0.4, stagger: 0.1, ease: 'power3.out' }, '-=0.2')
      .from('[data-dmba-hero="ctas"]', { opacity: 0, y: 15, duration: 0.5, ease: 'power3.out' }, '-=0.2')
      .from('[data-dmba-hero="visual"]', { opacity: 0, scale: 0.95, duration: 0.8, ease: 'power3.out' }, '-=0.5')
      .from('.dmba-hero__image-badge', { opacity: 0, x: -20, duration: 0.5, ease: 'power3.out' }, '-=0.3')
      .from('.dmba-hero__image-accent', { opacity: 0, scale: 0.8, duration: 0.5, ease: 'power3.out' }, '-=0.3');
  }

  // =============================================
  // TRUST BAR — Sequential Logo Fade
  // =============================================
  gsap.from('.dmba-trust__logo', {
    scrollTrigger: { trigger: '.dmba-trust', start: 'top 85%', once: true },
    opacity: 0,
    y: 15,
    duration: 0.5,
    stagger: 0.15,
    ease: 'power3.out'
  });

  // =============================================
  // PROGRAMME OVERVIEW — Cards Slide In
  // =============================================
  gsap.from('.dmba-overview__card', {
    scrollTrigger: { trigger: '.dmba-overview__pathway', start: 'top 75%', once: true },
    opacity: 0,
    y: 30,
    duration: 0.6,
    stagger: 0.2,
    ease: 'power3.out'
  });

  gsap.from('.dmba-overview__bridge', {
    scrollTrigger: { trigger: '.dmba-overview__pathway', start: 'top 75%', once: true },
    opacity: 0,
    scale: 0.8,
    duration: 0.6,
    delay: 0.3,
    ease: 'power3.out'
  });

  // =============================================
  // WHY CHOOSE — Staggered Card Reveal
  // =============================================
  gsap.from('.dmba-why__card', {
    scrollTrigger: { trigger: '.dmba-why__grid', start: 'top 80%', once: true },
    opacity: 0,
    y: 30,
    duration: 0.5,
    stagger: 0.1,
    ease: 'power3.out'
  });

  // =============================================
  // SPECIALISATIONS — Grid Cascade
  // =============================================
  gsap.from('.dmba-specs__card', {
    scrollTrigger: { trigger: '.dmba-specs__grid', start: 'top 80%', once: true },
    opacity: 0,
    y: 25,
    duration: 0.4,
    stagger: 0.08,
    ease: 'power3.out'
  });

  // =============================================
  // EMPLOYERS — Counter + List Reveal
  // =============================================
  // Counter animation
  const counterEl = document.querySelector('[data-dmba-counter]');
  if (counterEl) {
    const targetVal = parseInt(counterEl.getAttribute('data-dmba-counter'), 10);
    const counterObj = { val: 0 };
    ScrollTrigger.create({
      trigger: counterEl,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.to(counterObj, {
          val: targetVal,
          duration: 1.5,
          ease: 'power2.out',
          onUpdate: function () {
            counterEl.textContent = Math.round(counterObj.val);
          }
        });
      }
    });
  }

  gsap.from('.dmba-employers__item', {
    scrollTrigger: { trigger: '.dmba-employers__list', start: 'top 80%', once: true },
    opacity: 0,
    x: -15,
    duration: 0.4,
    stagger: 0.08,
    ease: 'power3.out'
  });

  // =============================================
  // PROCESS STEPS — Sequential Pop-in
  // =============================================
  gsap.from('.dmba-process__step', {
    scrollTrigger: { trigger: '.dmba-process__steps', start: 'top 80%', once: true },
    opacity: 0,
    y: 25,
    duration: 0.5,
    stagger: 0.15,
    ease: 'power3.out'
  });

  // =============================================
  // SECTION HEADERS — Fade Up
  // =============================================
  document.querySelectorAll('.dmba-overview__header, .dmba-why__header, .dmba-specs__header, .dmba-testimonials__header, .dmba-process__header, .dmba-faq__header').forEach(header => {
    gsap.from(header, {
      scrollTrigger: { trigger: header, start: 'top 85%', once: true },
      opacity: 0,
      y: 30,
      duration: 0.6,
      ease: 'power3.out'
    });
  });

  // =============================================
  // FINAL CTA — Parallax + Text
  // =============================================
  const ctaBg = document.querySelector('.dmba-cta__bg-image');
  if (ctaBg) {
    gsap.to(ctaBg, {
      scrollTrigger: {
        trigger: '.dmba-cta',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      },
      y: 60,
      ease: 'none'
    });
  }

  gsap.from('.dmba-cta__content > *', {
    scrollTrigger: { trigger: '.dmba-cta__content', start: 'top 80%', once: true },
    opacity: 0,
    y: 25,
    duration: 0.5,
    stagger: 0.12,
    ease: 'power3.out'
  });

  // =============================================
  // FAQ ACCORDION
  // =============================================
  document.querySelectorAll('.dmba-faq__question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.dmba-faq__item');
      const answer = item.querySelector('.dmba-faq__answer');
      const isOpen = item.classList.contains('is-open');

      // Close all others
      document.querySelectorAll('.dmba-faq__item.is-open').forEach(openItem => {
        if (openItem !== item) {
          openItem.classList.remove('is-open');
          openItem.querySelector('.dmba-faq__answer').style.maxHeight = '0';
        }
      });

      // Toggle current
      if (isOpen) {
        item.classList.remove('is-open');
        answer.style.maxHeight = '0';
      } else {
        item.classList.add('is-open');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });

  // =============================================
  // TESTIMONIAL CAROUSEL
  // =============================================
  const track = document.querySelector('.dmba-testimonials__track');
  const cards = document.querySelectorAll('.dmba-testimonials__card');
  const prevBtn = document.querySelector('[data-dmba-carousel="prev"]');
  const nextBtn = document.querySelector('[data-dmba-carousel="next"]');
  const dots = document.querySelectorAll('.dmba-testimonials__dot');

  if (track && cards.length > 0) {
    let currentIndex = 0;
    const cardsPerView = window.innerWidth > 768 ? 3 : 1;
    const maxIndex = Math.max(0, cards.length - cardsPerView);

    function goToSlide(index) {
      currentIndex = Math.max(0, Math.min(index, maxIndex));
      const cardWidth = cards[0].offsetWidth + 24; // card width + gap
      track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;

      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === currentIndex);
      });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => goToSlide(i)));

    // Auto-play
    let autoPlay = setInterval(() => goToSlide(currentIndex + 1 > maxIndex ? 0 : currentIndex + 1), 5000);

    track.addEventListener('mouseenter', () => clearInterval(autoPlay));
    track.addEventListener('mouseleave', () => {
      autoPlay = setInterval(() => goToSlide(currentIndex + 1 > maxIndex ? 0 : currentIndex + 1), 5000);
    });
  }
});
