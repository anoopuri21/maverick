document.addEventListener('DOMContentLoaded', () => {
    if (typeof AnimationUtils === 'undefined' || typeof gsap === 'undefined') return;

    const reducedMotion = AnimationUtils.prefersReducedMotion;

    function initTextReveals() {
        const inners = document.querySelectorAll('.page-mp .text-reveal-inner');
        if (!inners.length) return;

        const sections = new Map();
        inners.forEach((el) => {
            const section = el.closest('section');
            if (!section) return;
            if (!sections.has(section)) sections.set(section, []);
            sections.get(section).push(el);
        });

        sections.forEach((els, section) => {
            gsap.fromTo(
                els,
                { y: '110%' },
                {
                    y: '0%',
                    duration: 0.9,
                    stagger: 0.12,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 78%',
                        once: true,
                    },
                },
            );
        });
    }

    if (reducedMotion) {
        gsap.set('.page-mp .text-reveal-inner', { y: '0%' });
        gsap.set('.page-mp .fade-up, .page-mp .mp-pathway__phase, .page-mp .mp-how__phase, .page-mp .mp-dest__content', {
            clearProps: 'all', opacity: 1, y: 0,
        });
        return;
    }

    initTextReveals();

    const pathway = document.querySelector('.mp-pathway');
    if (pathway) {
        const phases = pathway.querySelectorAll('.mp-pathway__phase');
        phases.forEach((el, i) => {
            const last = i === phases.length - 1;
            const x = i === 0 ? -40 : (last && phases.length > 1 ? 40 : 0);
            gsap.from(el, {
                scrollTrigger: { trigger: pathway, start: 'top 80%', once: true },
                opacity: 0,
                x,
                y: x === 0 ? 24 : 0,
                duration: 0.7,
                ease: 'power3.out',
            });
        });
        gsap.from('.mp-pathway__connector-line', {
            scrollTrigger: { trigger: pathway, start: 'top 80%', once: true },
            scaleX: 0,
            transformOrigin: 'left center',
            duration: 0.6,
            ease: 'power2.out',
        });
    }

    gsap.from('.mp-how__phase', {
        scrollTrigger: { trigger: '.mp-how__phases', start: 'top 80%', once: true },
        opacity: 0,
        y: 40,
        stagger: 0.2,
        duration: 0.7,
        ease: 'power3.out',
    });

    AnimationUtils.fadeUp('.mp-benefit', { stagger: 0.1 });
    AnimationUtils.fadeUp('.mp-audience__item', { stagger: 0.06 });
    AnimationUtils.fadeUp('.mp-requirements__item', { stagger: 0.06 });
    AnimationUtils.fadeUp('.mp-dest__content', { stagger: 0.1 });

    document.querySelectorAll('.page-mp .fade-up').forEach((el) => {
        if (el.classList.contains('mp-benefit')) return;
        if (el.classList.contains('mp-audience__item')) return;
        if (el.classList.contains('mp-requirements__item')) return;
        if (el.classList.contains('mp-timeline__card')) return;
        if (el.closest('.mp-dest__content')) return;

        el.setAttribute('data-mp-fade', 'done');
        gsap.fromTo(el,
            { opacity: 0, y: 30 },
            {
                opacity: 1, y: 0,
                duration: 0.6,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 92%',
                    once: true,
                },
            },
        );
    });

    requestAnimationFrame(() => {
        document.querySelectorAll('.page-mp .fade-up').forEach((el) => {
            if (parseFloat(getComputedStyle(el).opacity) < 0.5) {
                const r = el.getBoundingClientRect();
                if (r.top < window.innerHeight && r.bottom > 0) {
                    gsap.to(el, { opacity: 1, y: 0, duration: 0.4, overwrite: true });
                }
            }
        });
    });

    const timeline = document.querySelector('.mp-timeline');
    const progress = document.querySelector('.mp-timeline__progress');
    if (timeline) {
        if (progress) {
            gsap.fromTo(progress, { height: '0%' }, {
                height: '100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: timeline,
                    start: 'top 70%',
                    end: 'bottom 60%',
                    scrub: 0.6,
                },
            });
        }
        const cards = timeline.querySelectorAll('.mp-timeline__card');
        gsap.fromTo(cards, { opacity: 0, x: (i) => (i % 2 === 0 ? -40 : 40), y: 20 }, {
            scrollTrigger: { trigger: timeline, start: 'top 70%', once: true },
            opacity: 1, x: 0, y: 0, stagger: 0.15, duration: 0.6, ease: 'power3.out',
        });
    }
});
