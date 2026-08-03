/**
 * Edutainment Page — Animations
 * Uses AnimationUtils for consistent scroll animations
 */

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
      .from('.edu-hero__tag', { opacity: 0, x: -20, duration: 0.6, ease: 'power3.out' })
      .from('.edu-hero__title', { opacity: 0, y: 30, duration: 0.7, ease: 'power3.out' }, '-=0.3')
      .from('.edu-hero__subtitle', { opacity: 0, y: 20, duration: 0.6, ease: 'power3.out' }, '-=0.3')
      .from('.edu-hero__description', { opacity: 0, y: 20, duration: 0.6, ease: 'power3.out' }, '-=0.3')
      .from('.edu-hero__highlights .edu-hero__highlight', { opacity: 0, y: 10, duration: 0.3, stagger: 0.05, ease: 'power3.out' }, '-=0.2')
      .from('.edu-hero__emphasis', { opacity: 0, y: 15, duration: 0.5, ease: 'power3.out' }, '-=0.2')
      .from('.edu-hero__ctas', { opacity: 0, y: 15, duration: 0.5, ease: 'power3.out' }, '-=0.2');
  }

  // =============================================
  // GENERIC FADE-UP ANIMATION
  // =============================================
  document.querySelectorAll('.fade-up').forEach(el => {
    gsap.from(el, {
      scrollTrigger: { trigger: el, start: 'top 90%', once: true },
      opacity: 0,
      y: 30,
      duration: 0.6,
      ease: 'power3.out'
    });
  });

  // =============================================
  // SECTION HEADERS — Fade Up
  // =============================================
  document.querySelectorAll('.section-title, .section-label').forEach(header => {
    gsap.from(header, {
      scrollTrigger: { trigger: header, start: 'top 90%', once: true },
      opacity: 0,
      y: 20,
      duration: 0.5,
      ease: 'power3.out'
    });
  });

  // =============================================
  // CARD GRIDS — Staggered Reveal
  // =============================================
  const cardSelectors = [
    '.edu-learning-beyond__card',
    '.edu-who-for__card',
    '.edu-themes__card',
    '.edu-experiences__card',
    '.edu-institutions__card',
    '.edu-packages__item'
  ];

  cardSelectors.forEach(selector => {
    const cards = document.querySelectorAll(selector);
    if (cards.length) {
      gsap.from(cards, {
        scrollTrigger: { trigger: cards[0].closest('section'), start: 'top 80%', once: true },
        opacity: 0,
        y: 30,
        duration: 0.5,
        stagger: 0.08,
        ease: 'power3.out'
      });
    }
  });

  // =============================================
  // PROGRAMME CARDS — Slide In
  // =============================================
  document.querySelectorAll('.edu-programmes__card').forEach(card => {
    gsap.from(card, {
      scrollTrigger: { trigger: card, start: 'top 85%', once: true },
      opacity: 0,
      y: 40,
      duration: 0.7,
      ease: 'power3.out'
    });
  });

  // =============================================
  // WHY CHOOSE — Image + List
  // =============================================
  const whyImage = document.querySelector('.edu-why-choose__image-wrapper');
  if (whyImage) {
    gsap.from(whyImage, {
      scrollTrigger: { trigger: whyImage, start: 'top 80%', once: true },
      opacity: 0,
      x: -40,
      duration: 0.8,
      ease: 'power3.out'
    });
  }

  document.querySelectorAll('.edu-why-choose__item').forEach((item, i) => {
    gsap.from(item, {
      scrollTrigger: { trigger: item, start: 'top 90%', once: true },
      opacity: 0,
      x: 30,
      duration: 0.5,
      delay: i * 0.1,
      ease: 'power3.out'
    });
  });

  // =============================================
  // FAQ ACCORDION
  // =============================================
  document.querySelectorAll('.edu-faq__question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.edu-faq__item');
      const answer = item.querySelector('.edu-faq__answer');
      const isOpen = item.classList.contains('is-open');

      // Close all others
      document.querySelectorAll('.edu-faq__item.is-open').forEach(openItem => {
        if (openItem !== item) {
          openItem.classList.remove('is-open');
          openItem.querySelector('.edu-faq__answer').style.maxHeight = '0';
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
  // FINAL CTA — Parallax
  // =============================================
  const ctaBg = document.querySelector('.edu-cta__bg-image');
  if (ctaBg) {
    gsap.to(ctaBg, {
      scrollTrigger: {
        trigger: '.edu-cta',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      },
      y: 60,
      ease: 'none'
    });
  }

  // =============================================
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // =============================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        const offset = 100;
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
});
