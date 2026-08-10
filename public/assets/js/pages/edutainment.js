/**
 * Edutainment Page — Animations
 * Uses GSAP + ScrollTrigger; .fade-up uses fromTo so CSS opacity:0 can resolve to visible
 */

document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    document.querySelectorAll('.fade-up').forEach((el) => {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function revealFadeUps() {
    document.querySelectorAll('.fade-up').forEach((el) => {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
  }

  if (prefersReducedMotion) {
    revealFadeUps();
  } else {
    // Hero — staggered entrance (fromTo so CSS .fade-up opacity:0 is not the end state)
    const heroTl = gsap.timeline({ delay: 0.3 });
    heroTl
      .fromTo('.edu-hero__tag', { opacity: 0, y: -20 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' })
      .fromTo('.edu-hero__title', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, '-=0.3')
      .fromTo('.edu-hero__shortdesc', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3');

    // Intro section — statement + body stagger reveal
    const introTl = gsap.timeline({
      scrollTrigger: { trigger: '.edu-intro', start: 'top 75%', once: true },
    });
    introTl
      .fromTo('.edu-intro__label', { opacity: 0, y: 15 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' })
      .fromTo('.edu-intro__title', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3')
      .fromTo('.edu-intro__body p', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.1, ease: 'power3.out' }, '-=0.3')
      .fromTo('.edu-intro__emphasis', { opacity: 0, y: 15 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' }, '-=0.2')
      .fromTo('.edu-intro__ctas', { opacity: 0, y: 15 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' }, '-=0.2');

    // Generic .fade-up — skip hero (handled above) to avoid GSAP conflicts
    document.querySelectorAll('.fade-up').forEach((el) => {
      if (el.closest('.edu-hero')) return;

      gsap.fromTo(
        el,
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: 'power3.out',
          scrollTrigger: { trigger: el, start: 'top 90%', once: true },
        },
      );
    });

    // Section headers — only when not already covered by .fade-up
    document.querySelectorAll('.section-title, .section-label').forEach((header) => {
      if (header.classList.contains('fade-up') || header.closest('.fade-up')) return;

      gsap.fromTo(
        header,
        { opacity: 0, y: 20 },
        {
          opacity: 1,
          y: 0,
          duration: 0.5,
          ease: 'power3.out',
          scrollTrigger: { trigger: header, start: 'top 90%', once: true },
        },
      );
    });

    // Card grids — stagger only cards without .fade-up (those use the generic reveal)
    const cardSelectors = [
      '.edu-learning-beyond__card',
      '.edu-who-for__card',
      '.edu-themes__card',
      '.edu-experiences__card',
      '.edu-why-choose__card',
      '.edu-institutions__card',
      '.edu-packages__item',
      '.edu-programmes__china-item',
    ];

    cardSelectors.forEach((selector) => {
      const cards = Array.from(document.querySelectorAll(selector)).filter(
        (card) => !card.classList.contains('fade-up'),
      );
      if (!cards.length) return;

      gsap.fromTo(
        cards,
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.5,
          stagger: 0.08,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: cards[0].closest('section') || cards[0],
            start: 'top 80%',
            once: true,
          },
        },
      );
    });

    // Programme cards — skip .fade-up duplicates
    document.querySelectorAll('.edu-programmes__card').forEach((card) => {
      if (card.classList.contains('fade-up')) return;

      gsap.fromTo(
        card,
        { opacity: 0, y: 40 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          ease: 'power3.out',
          scrollTrigger: { trigger: card, start: 'top 85%', once: true },
        },
      );
    });
  }

  // =============================================
  // FAQ ACCORDION
  // =============================================
  document.querySelectorAll('.edu-faq__question').forEach((btn) => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.edu-faq__item');
      const answer = item.querySelector('.edu-faq__answer');
      const isOpen = item.classList.contains('is-open');

      document.querySelectorAll('.edu-faq__item.is-open').forEach((openItem) => {
        if (openItem !== item) {
          openItem.classList.remove('is-open');
          openItem.querySelector('.edu-faq__answer').style.maxHeight = '0';
        }
      });

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
  if (!prefersReducedMotion) {
    const ctaBg = document.querySelector('.edu-cta__bg-image');
    if (ctaBg) {
      gsap.to(ctaBg, {
        scrollTrigger: {
          trigger: '.edu-cta',
          start: 'top bottom',
          end: 'bottom top',
          scrub: true,
        },
        y: 60,
        ease: 'none',
      });
    }
  }

  // =============================================
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // =============================================
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        const offset = 100;
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth',
        });
      }
    });
  });
});
