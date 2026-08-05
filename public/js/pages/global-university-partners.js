// ============================================
// GLOBAL UNIVERSITY PARTNERS - Animations & Interactions
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // Check for GSAP
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.warn('GSAP or ScrollTrigger not loaded');
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ═══════════════════════════════════════════
    // TEXT REVEAL ANIMATION
    // ═══════════════════════════════════════════
    const textReveals = document.querySelectorAll('.text-reveal-inner');
    if (textReveals.length && !prefersReducedMotion) {
        gsap.set(textReveals, { y: '110%' });
        gsap.to(textReveals, {
            y: '0%',
            duration: 0.9,
            stagger: 0.12,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '#university-partners',
                start: 'top 75%',
                toggleActions: 'play none none none',
            },
        });
    }

    // ═══════════════════════════════════════════
    // SECTION LABEL ANIMATION
    // ═══════════════════════════════════════════
    const sectionLabel = document.querySelector('#university-partners .section-label');
    if (sectionLabel && !prefersReducedMotion) {
        gsap.fromTo(sectionLabel,
            { opacity: 0, y: 16 },
            {
                opacity: 1,
                y: 0,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '#university-partners',
                    start: 'top 75%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    // ═══════════════════════════════════════════
    // DETAIL PANEL ANIMATION
    // ═══════════════════════════════════════════
    const detailPanel = document.querySelector('.partners__detail-panel');
    if (detailPanel && !prefersReducedMotion) {
        gsap.fromTo(detailPanel,
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.partners__detail-panel',
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    // ═══════════════════════════════════════════
    // MOBILE ITEMS ANIMATION
    // ═══════════════════════════════════════════
    const mobileItems = document.querySelectorAll('.partners__mobile-item');
    if (mobileItems.length && !prefersReducedMotion) {
        gsap.fromTo(mobileItems,
            { opacity: 0, x: -20 },
            {
                opacity: 1,
                x: 0,
                duration: 0.5,
                stagger: 0.05,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.partners__mobile-list',
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    // ═══════════════════════════════════════════
    // PARTNER UNIVERSITIES — 3D Tilt + Scroll Reveal
    // ═══════════════════════════════════════════
    const uniCards = document.querySelectorAll('.gup-uni-card');

    // Scroll-reveal animation
    if (uniCards.length && !prefersReducedMotion) {
        gsap.fromTo(uniCards,
            { opacity: 0, y: 50, rotateX: 8 },
            {
                opacity: 1,
                y: 0,
                rotateX: 0,
                duration: 0.8,
                stagger: 0.12,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.gup-partner-cards__grid',
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    // 3D tilt on mousemove
    if (uniCards.length && !prefersReducedMotion && window.matchMedia('(pointer: fine)').matches) {
        uniCards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -7;
                const rotateY = ((x - centerX) / centerX) * 7;

                gsap.to(card, {
                    rotateX: rotateX,
                    rotateY: rotateY,
                    y: -6,
                    duration: 0.35,
                    ease: 'power2.out',
                    transformPerspective: 1200,
                    transformOrigin: 'center center',
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    rotateX: 0,
                    rotateY: 0,
                    y: 0,
                    duration: 0.55,
                    ease: 'power3.out',
                });
            });
        });
    }

    // ═══════════════════════════════════════════
    // GALLERY FILTER
    // ═══════════════════════════════════════════
    const filterButtons = document.querySelectorAll('.gup-filter');
    const galleryItems = document.querySelectorAll('.gup-gallery-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const filter = this.dataset.filter;

            // Update active state
            filterButtons.forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');

            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.classList.remove('is-hidden');
                } else {
                    item.classList.add('is-hidden');
                }
            });
        });
    });

    // ═══════════════════════════════════════════
    // SCROLL REVEAL (Intersection Observer)
    // ═══════════════════════════════════════════
    const revealElements = document.querySelectorAll(
        '.gup-stat, .gup-why-card, .gup-benefit, .gup-gallery-item'
    );

    if (revealElements.length) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -80px 0px'
        });

        revealElements.forEach(el => observer.observe(el));
    }

});
