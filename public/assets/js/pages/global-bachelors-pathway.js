document.addEventListener('DOMContentLoaded', () => {
    if (typeof AnimationUtils === 'undefined' || typeof gsap === 'undefined') return;

    if (AnimationUtils.prefersReducedMotion) {
        gsap.set('.fade-up, .text-reveal-inner, .gbp-snapshot-card, .gbp-intro-card, .gbp-why-card, .gbp-explore-card, .gbp-area-card, .gbp-partner-card, .gbp-doc-card', {
            clearProps: 'all',
            opacity: 1,
            y: 0,
            scaleY: 1,
        });
        return;
    }

    AnimationUtils.cards('.gbp-snapshot-card', { stagger: 0.12 });
    AnimationUtils.fadeUp('.gbp-snapshot .fade-up', { stagger: 0.1 });

    AnimationUtils.textReveal('.gbp-intro .text-reveal-inner', { stagger: 0.15 });
    AnimationUtils.fadeUp('.gbp-intro .fade-up', { stagger: 0.12, y: 25 });
    AnimationUtils.cards('.gbp-intro-card', { stagger: 0.1, y: 30 });

    const sections = [
        '.gbp-overview', '.gbp-why', '.gbp-explore',
        '.gbp-destinations', '.gbp-cost', '.gbp-comparison', '.gbp-areas', '.gbp-partners',
        '.gbp-admission', '.gbp-docs', '.gbp-final',
    ];

    sections.forEach((s) => {
        if (document.querySelector(s)) AnimationUtils.sectionLabel(s);
    });

    [
        '.gbp-overview', '.gbp-why', '.gbp-explore',
        '.gbp-destinations', '.gbp-cost', '.gbp-areas', '.gbp-partners',
        '.gbp-admission', '.gbp-docs',
    ].forEach((s) => {
        if (document.querySelector(`${s} .text-reveal-inner`)) {
            AnimationUtils.textReveal(`${s} .text-reveal-inner`);
        }
    });

    sections.forEach((s) => {
        if (document.querySelector(`${s} .fade-up`)) {
            AnimationUtils.fadeUp(`${s} .fade-up`, { stagger: 0.1 });
        }
    });

    if (document.querySelector('.gbp-comparison .text-reveal-inner')) {
        AnimationUtils.textReveal('.gbp-comparison .text-reveal-inner', {
            duration: 0.8,
            stagger: 0.15,
            ease: 'power2.out',
        });
    }

    if (document.querySelector('.gbp-comparison__grid')) {
        gsap.fromTo('.gbp-comparison-card',
            { opacity: 0, y: 50 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.gbp-comparison__grid',
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
            }
        );

        gsap.fromTo('.gbp-comparison-card__price-panel',
            { opacity: 0, scale: 0.9 },
            {
                opacity: 1,
                scale: 1,
                duration: 0.4,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.gbp-comparison__grid',
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
                delay: 0.45,
            }
        );

        document.querySelectorAll('.gbp-comparison-card').forEach((card) => {
            const bullets = card.querySelectorAll('.gbp-comparison-card__bullets li');
            if (!bullets.length) return;
            gsap.fromTo(bullets,
                { opacity: 0, x: -10 },
                {
                    opacity: 1,
                    x: 0,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 85%',
                        toggleActions: 'play none none none',
                    },
                }
            );
        });
    }

    if (document.querySelector('.gbp-comparison-callout')) {
        gsap.fromTo('.gbp-comparison-callout',
            { opacity: 0, y: 30 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.gbp-comparison-callout',
                    start: 'top 90%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    AnimationUtils.cards('.gbp-why-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-explore-card', { stagger: 0.15 });
    AnimationUtils.cards('.gbp-area-card', { stagger: 0.1 });
    AnimationUtils.cards('.gbp-partner-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-doc-card', { stagger: 0.12 });

    const roadmapStages = document.querySelectorAll('.gbp-roadmap-stage');
    const roadmapPath = document.querySelector('.gbp-roadmap-path');
    const roadmapPathBase = document.querySelector('.gbp-roadmap-path-base');
    const roadmapMarkers = document.querySelectorAll('.gbp-roadmap-stage__marker');
    const roadmapCards = document.querySelectorAll('.gbp-roadmap-stage__card');
    const roadmapPanelItems = document.querySelectorAll('.gbp-roadmap-panel__item');
    const roadmapPanelNums = document.querySelectorAll('.gbp-roadmap-panel__step-num');

    if (!roadmapStages.length) return;

    if (roadmapPath) {
        gsap.fromTo(roadmapPath,
            { strokeDashoffset: 1200 },
            {
                strokeDashoffset: 0,
                duration: 2.4,
                ease: 'power2.inOut',
                scrollTrigger: {
                    trigger: '.gbp-roadmap',
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    if (roadmapPathBase) {
        gsap.fromTo(roadmapPathBase,
            { opacity: 0 },
            {
                opacity: 1,
                duration: 1.0,
                delay: 0.3,
                ease: 'power1.out',
                scrollTrigger: {
                    trigger: '.gbp-roadmap',
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    roadmapMarkers.forEach((marker, i) => {
        const ring = marker.querySelector('.gbp-roadmap-stage__marker-ring');
        if (ring) {
            gsap.fromTo(ring,
                { opacity: 0 },
                {
                    opacity: 1,
                    duration: 2.8,
                    delay: i * 0.35,
                    repeat: -1,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '.gbp-roadmap',
                        start: 'top 75%',
                        toggleActions: 'play none none none',
                    },
                }
            );
        }
    });

    roadmapMarkers.forEach((marker, i) => {
        const isLeft = i % 2 === 0;
        gsap.fromTo(marker,
            { opacity: 0, rotate: isLeft ? -15 : 15 },
            {
                opacity: 1, rotate: 0,
                duration: 0.65,
                delay: 0.35 + i * 0.18,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: '.gbp-roadmap',
                    start: 'top 72%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    roadmapCards.forEach((card, i) => {
        const isLeft = i % 2 === 0;
        gsap.fromTo(card,
            { opacity: 0, x: isLeft ? -40 : 40, scale: 0.93 },
            {
                opacity: 1, x: 0, scale: 1,
                duration: 0.75,
                delay: 0.45 + i * 0.18,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.gbp-roadmap',
                    start: 'top 68%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    roadmapPanelItems.forEach((item, i) => {
        gsap.fromTo(item,
            { opacity: 0, x: 30 },
            {
                opacity: 1, x: 0,
                duration: 0.55,
                delay: 0.6 + i * 0.12,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.gbp-roadmap-panel',
                    start: 'top 70%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    roadmapPanelNums.forEach((num, i) => {
        gsap.fromTo(num,
            { opacity: 0 },
            {
                opacity: 1,
                duration: 0.45,
                delay: 1.75 + i * 0.12,
                ease: 'back.out(2)',
                scrollTrigger: {
                    trigger: '.gbp-roadmap-panel',
                    start: 'top 70%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    document.querySelectorAll('.gbp-roadmap-panel__stat').forEach((stat, i) => {
        gsap.fromTo(stat,
            { opacity: 0, y: 12 },
            {
                opacity: 1, y: 0,
                duration: 0.4,
                delay: 1.0 + i * 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.gbp-roadmap-panel',
                    start: 'top 65%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    roadmapMarkers.forEach((marker) => {
        gsap.to(marker, {
            scale: 1.05,
            duration: 2.0,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            scrollTrigger: {
                trigger: '.gbp-roadmap',
                start: 'top 70%',
                end: 'bottom 30%',
                scrub: 1.2,
            },
        });
    });
});
