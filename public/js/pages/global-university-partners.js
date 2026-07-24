// ============================================
// GLOBAL UNIVERSITY PARTNERS - Filter Logic
// ============================================

document.addEventListener('DOMContentLoaded', function () {

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

    if (!revealElements.length) return;

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

});