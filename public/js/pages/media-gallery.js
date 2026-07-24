// ============================================
// MEDIA GALLERY - Filter + Slider
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════════════
    // 1. GALLERY FILTER
    // ═══════════════════════════════════════════
    const filterButtons = document.querySelectorAll('.gallery-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');

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
    // 2. RECENT EVENTS SLIDER
    // ═══════════════════════════════════════════
    const slider = document.querySelector('[data-events-slider]');
    if (!slider) return;

    const track = slider.querySelector('.recent-events__track');
    const prevBtn = slider.querySelector('[data-events-prev]');
    const nextBtn = slider.querySelector('[data-events-next]');
    const cards = track.querySelectorAll('.event-card');
    
    let currentIndex = 0;
    const getVisibleCards = () => {
        const width = window.innerWidth;
        if (width < 640) return 1;
        if (width < 991) return 2;
        return 3;
    };

    function updateSlider() {
        const cardWidth = cards[0].offsetWidth + 20;
        track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    nextBtn.addEventListener('click', () => {
        const visibleCards = getVisibleCards();
        if (currentIndex < cards.length - visibleCards) {
            currentIndex++;
            updateSlider();
        }
    });

    window.addEventListener('resize', () => {
        currentIndex = 0;
        updateSlider();
    });

});