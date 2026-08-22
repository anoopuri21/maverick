document.addEventListener('DOMContentLoaded', () => {
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.querySelectorAll('.fv-card').forEach((card, index) => {
    if (prefersReducedMotion) {
      card.classList.add('is-revealed');
      return;
    }

    const reveal = () => {
      card.style.transitionDelay = `${Math.min(index, 6) * 60}ms`;
      card.classList.add('is-revealed');
    };

    if (!('IntersectionObserver' in window)) {
      reveal();
      return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          reveal();
          obs.disconnect();
        }
      });
    }, { threshold: 0.15 });

    observer.observe(card);
  });

  const fill = document.getElementById('fv-progress-fill');
  const scaleEl = document.querySelector('[data-fv-scale]');
  const scaleSection = scaleEl?.closest('.fv-featured');
  const SCALE_START = 0.15;
  const SCALE_END = 1;
  const SCALE_AT = 0.9;
  let scaleCurrent = SCALE_START;
  let scaleTarget = SCALE_START;
  let scaleRaf = 0;

  const updateProgress = () => {
    if (fill) {
      const doc = document.documentElement;
      const max = doc.scrollHeight - window.innerHeight;
      const value = max > 0 ? (window.scrollY / max) * 100 : 0;
      fill.style.width = `${Math.min(100, Math.max(0, value))}%`;
    }
  };

  const featuredScaleTarget = () => {
    if (!scaleEl || !scaleSection) {
      return SCALE_END;
    }

    const rect = scaleSection.getBoundingClientRect();
    const vh = window.innerHeight;
    const total = rect.height + vh;
    const scrolled = vh - rect.top;
    const progress = total > 0 ? scrolled / total : 0;
    const t = Math.min(1, Math.max(0, progress / SCALE_AT));
    const eased = 1 - Math.pow(1 - t, 3);

    return SCALE_START + ((SCALE_END - SCALE_START) * eased);
  };

  const tickScale = () => {
    scaleCurrent += (scaleTarget - scaleCurrent) * 0.12;

    if (Math.abs(scaleTarget - scaleCurrent) < 0.001) {
      scaleCurrent = scaleTarget;
      scaleRaf = 0;
    } else {
      scaleRaf = requestAnimationFrame(tickScale);
    }

    scaleEl.style.transform = `scale(${scaleCurrent})`;
  };

  const updateFeaturedScale = () => {
    if (!scaleEl || !scaleSection || prefersReducedMotion) {
      return;
    }

    scaleTarget = featuredScaleTarget();

    if (!scaleRaf) {
      scaleRaf = requestAnimationFrame(tickScale);
    }
  };

  const onScroll = () => {
    updateProgress();
    updateFeaturedScale();
  };

  if (scaleEl && prefersReducedMotion) {
    scaleEl.style.transform = 'scale(1)';
  }

  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
});
