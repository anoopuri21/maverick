/**
 * Edutainment Page — Animations
 * Uses GSAP + ScrollTrigger; .fade-up uses fromTo so CSS opacity:0 can resolve to visible
 */

document.addEventListener('DOMContentLoaded', () => {
  // Graceful fallback if GSAP is not loaded
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    revealAllElements();
    setupFaqFallback();
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function revealAllElements() {
    document.querySelectorAll('.fade-up, .text-reveal-inner').forEach((el) => {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
  }

  function setupFaqFallback() {
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
          answer.style.maxHeight = 'none';
        }
      });
    });
  }

  if (prefersReducedMotion) {
    revealAllElements();
    setupFaqFallback();
    return;
  }

  // 1. Heading Text Reveal Animation
  document.querySelectorAll('section').forEach((section) => {
    const textInners = section.querySelectorAll('.text-reveal-inner');
    if (!textInners.length) return;

    gsap.fromTo(
      textInners,
      { y: '110%' },
      {
        y: '0%',
        duration: 0.9,
        stagger: 0.1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: section,
          start: 'top 80%',
          once: true,
        },
      }
    );
  });

  // 2. Staggered Fade Up for Content / Paragraphs
  document.querySelectorAll('.fade-up').forEach((el) => {
    // Skip if element is card list or specific card collections to be staggered as groups
    if (
      el.closest('.edu-learning-beyond__grid') ||
      el.closest('.edu-who-for__grid') ||
      el.closest('.edu-themes__grid') ||
      el.closest('.edu-experiences__grid') ||
      el.closest('.edu-institutions__grid') ||
      el.closest('.edu-packages__grid') ||
      el.closest('.edu-why-choose__list')
    ) {
      return;
    }

    gsap.fromTo(
      el,
      { opacity: 0, y: 30 },
      {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: el,
          start: 'top 85%',
          once: true,
        },
      }
    );
  });

  // 3. Card Grids Staggered Animation
  const gridSelectors = [
    { grid: '.edu-learning-beyond__grid', cards: '.edu-learning-beyond__card' },
    { grid: '.edu-who-for__grid', cards: '.edu-who-for__card' },
    { grid: '.edu-themes__grid', cards: '.edu-themes__card' },
    { grid: '.edu-experiences__grid', cards: '.edu-experiences__card' },
    { grid: '.edu-packages__grid', cards: '.edu-packages__item' },
    { grid: '.edu-institutions__grid', cards: '.edu-institutions__card' }
  ];

  gridSelectors.forEach(({ grid, cards }) => {
    const gridEl = document.querySelector(grid);
    if (!gridEl) return;

    const cardElements = gridEl.querySelectorAll(cards);
    if (!cardElements.length) return;

    gsap.fromTo(
      cardElements,
      { opacity: 0, y: 30 },
      {
        opacity: 1,
        y: 0,
        duration: 0.6,
        stagger: 0.08,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: gridEl,
          start: 'top 80%',
          once: true,
        },
      }
    );
  });

  // 4. Programme Cards Staggered Animation
  document.querySelectorAll('.edu-programmes__card').forEach((card) => {
    gsap.fromTo(
      card,
      { opacity: 0, y: 40 },
      {
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: card,
          start: 'top 85%',
          once: true,
        },
      }
    );
  });

  // 5. Why Choose Section - Image & List Items
  const whyImage = document.querySelector('.edu-why-choose__image-wrapper');
  if (whyImage) {
    gsap.fromTo(
      whyImage,
      { opacity: 0, scale: 0.95, x: -30 },
      {
        opacity: 1,
        scale: 1,
        x: 0,
        duration: 0.8,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: whyImage,
          start: 'top 80%',
          once: true,
        },
      }
    );
  }

  const whyItems = document.querySelectorAll('.edu-why-choose__item');
  if (whyItems.length) {
    gsap.fromTo(
      whyItems,
      { opacity: 0, x: 30 },
      {
        opacity: 1,
        x: 0,
        duration: 0.5,
        stagger: 0.08,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '.edu-why-choose__list',
          start: 'top 80%',
          once: true,
        },
      }
    );
  }

  // 6. FAQ Accordion Interaction
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

  // Initialize first FAQ open height correctly on load (since first FAQ has is-open by default)
  const firstOpenAnswer = document.querySelector('.edu-faq__item.is-open .edu-faq__answer');
  if (firstOpenAnswer) {
    // Small timeout to allow styles/layouts to compute
    setTimeout(() => {
      firstOpenAnswer.style.maxHeight = firstOpenAnswer.scrollHeight + 'px';
    }, 100);
  }

  // 7. Final CTA Background Parallax Animation
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

  // 8. Smooth Scroll for Anchor Links
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
