document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const ctx = gsap.context(() => {
    gsap.registerPlugin(ScrollTrigger);

    const mm = gsap.matchMedia();

    mm.add('(min-width: 768px)', () => {
      initHero();
      initReveals();
      initCounters();
    });
  });

  window.addEventListener('beforeunload', () => ctx.revert());
});

function initHero() {
  // Parallax background
  gsap.to('.accred-hero', {
    backgroundPosition: '50% 25%',
    ease: 'none',
    scrollTrigger: {
      trigger: '.accred-hero',
      start: 'top top',
      end: 'bottom top',
      scrub: 1,
      invalidateOnRefresh: true
    }
  });

  gsap.timeline()
    .fromTo('.accred-hero__tag', 
      { opacity: 0, y: 30 }, 
      { opacity: 1, y: 0, duration: 1, ease: 'power3.out' })
    .fromTo('.accred-hero__heading', 
      { opacity: 0, y: 50 }, 
      { opacity: 1, y: 0, duration: 1.1, ease: 'power3.out' }, '-=0.6')
    .fromTo('.accred-hero__description', 
      { opacity: 0, y: 30 }, 
      { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out' }, '-=0.7');
}

function initReveals() {
  const sections = [
    { trigger: '.csr-commitment', elements: '.csr-commitment__content, .csr-commitment__visual', start: 'top 92%', stagger: 0.1 },
    { trigger: '.csr-focus', elements: '.csr-focus', start: 'top 80%', stagger: 0.1 },
    { trigger: '.csr-gallery', elements: '.csr-gallery', start: 'top 82%', stagger: 0.12 },
    { trigger: '.csr-scholarship', elements: '.csr-scholarship > *, .csr-checklist-card__text', start: 'top 50%', stagger: 0.1 }
  ];

  sections.forEach(s => {
    gsap.from(s.elements, {
      scrollTrigger: {
        trigger: s.trigger,
        start: s.start,
        once: true,
        toggleActions: 'play none none none'
      },
      opacity: 0,
      y: 200,
      duration: 1.5,
      stagger: s.stagger ?? 0.08,
      ease: 'power2.out'
    });
  });
}

function initCounters() {
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      document.querySelectorAll('.csr-impact-card__number').forEach(el => {
          const target = parseInt(el.dataset.target, 10) || 0;
          if (target) el.textContent = target.toLocaleString();
      });
      return;
  }

  gsap.registerPlugin(ScrollTrigger);

  document.querySelectorAll('.csr-impact-card__number').forEach(el => {
      const target = parseInt(el.dataset.target, 10) || 0;
      if (!target) return;

      gsap.fromTo(el, 
          { textContent: 0 },
          {
              scrollTrigger: {
                  trigger: el,
                  start: "top 85%",
                  once: true,
                  invalidateOnRefresh: true,
              },
              textContent: target,
              duration: 1.8,
              ease: "power2.out",
              snap: { textContent: 1 },
              onUpdate: function () {
                  el.textContent = Math.ceil(parseFloat(this.targets()[0].textContent))
                      .toLocaleString('en-IN');
              }
          }
      );
  });

  ScrollTrigger.refresh();
}

window.addEventListener('load', initCounters);