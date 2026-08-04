// ============================================
// MEDIA GALLERY — dynamic filter, load-more,
// lightbox, video modal + events slider
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════════════
    // 1. GALLERY FILTER + LOAD MORE
    // ═══════════════════════════════════════════
    const masonry = document.querySelector('[data-masonry]');
    const filterButtons = Array.from(document.querySelectorAll('.gallery-filter'));
    const galleryItems = masonry ? Array.from(masonry.querySelectorAll('.gallery-item')) : [];
    const loadMoreBtn = document.querySelector('[data-load-more="photos"]');

    const BATCH_SIZE = 8;
    let activeFilter = 'all';
    let loadedCount = BATCH_SIZE;

    function refreshVisibility() {
        const filtering = activeFilter !== 'all';
        galleryItems.forEach(function (item, i) {
            const matches = filtering || item.dataset.category === activeFilter;
            const loaded = filtering || i < loadedCount;
            const show = matches && loaded;
            item.classList.toggle('is-hidden', !show);
        });

        // Hide load-more when everything relevant is shown.
        if (loadMoreBtn) {
            const totalRelevant = filtering
                ? galleryItems.filter(function (it) {
                    return it.dataset.category === activeFilter;
                }).length
                : galleryItems.length;
            loadMoreBtn.closest('[data-load-more-wrap]').style.display =
                loadedCount >= totalRelevant ? 'none' : '';
        }
    }

    function updateFiltersActive(active) {
        filterButtons.forEach(function (btn) {
            btn.classList.toggle('is-active', btn.dataset.filter === active);
        });
    }

    if (filterButtons.length) {
        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeFilter = btn.dataset.filter || 'all';
                updateFiltersActive(activeFilter);
                refreshVisibility();
            });
        });
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            loadedCount += BATCH_SIZE;
            refreshVisibility();
        });
    }

    if (galleryItems.length) {
        refreshVisibility();
    }

    // ═══════════════════════════════════════════
    // 2. LIGHTBOX
    // ═══════════════════════════════════════════
    const lightbox = document.getElementById('lightbox');
    const lbImage = document.querySelector('[data-lightbox-image]');
    const lbCaption = document.querySelector('[data-lightbox-caption]');
    const lbCounter = document.querySelector('[data-lightbox-counter]');

    let lbItems = [];
    let lbIndex = 0;

    function visibleItems() {
        return galleryItems.filter(function (item) {
            return !item.classList.contains('is-hidden');
        });
    }

    function openLightbox(index) {
        lbItems = visibleItems();
        if (!lbItems.length) return;
        lbIndex = (index + lbItems.length) % lbItems.length;
        renderLightbox();
        lightbox.hidden = false;
        document.body.style.overflow = 'hidden';
        lbImage.focus();
    }

    function renderLightbox() {
        const item = lbItems[lbIndex];
        lbImage.src = item.dataset.src || '';
        lbImage.alt = item.dataset.caption || 'Gallery photo';
        lbCaption.textContent = item.dataset.caption || '';
        lbCounter.textContent = (lbIndex + 1) + ' / ' + lbItems.length;
    }

    function closeLightbox() {
        lightbox.hidden = true;
        document.body.style.overflow = '';
    }

    galleryItems.forEach(function (item) {
        item.addEventListener('click', function () {
            openLightbox(galleryItems.indexOf(item));
        });
        item.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openLightbox(galleryItems.indexOf(item));
            }
        });
    });

    const lbClose = document.querySelector('[data-lightbox-close]');
    const lbPrev = document.querySelector('[data-lightbox-prev]');
    const lbNext = document.querySelector('[data-lightbox-next]');

    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lbPrev) lbPrev.addEventListener('click', function () { openLightbox(lbIndex - 1); });
    if (lbNext) lbNext.addEventListener('click', function () { openLightbox(lbIndex + 1); });

    // ═══════════════════════════════════════════
    // 3. VIDEO MODAL
    // ═══════════════════════════════════════════
    const videoModal = document.getElementById('videoModal');
    const videoFrame = document.querySelector('[data-video-frame]');
    const videoClose = document.querySelector('[data-video-close]');

    function toEmbed(url) {
        const yt = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]{6,})/);
        if (yt) return '<iframe src="https://www.youtube.com/embed/' + yt[1] + '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
        const vimeo = url.match(/vimeo\.com\/(\d+)/);
        if (vimeo) return '<iframe src="https://player.vimeo.com/video/' + vimeo[1] + '" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>';
        if (/\.(mp4|webm|ogg|mov)$/i.test(url)) return '<video src="' + url + '" controls playsinline></video>';
        return '<iframe src="' + url + '" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>';
    }

    function openVideo(url) {
        if (!videoModal) return;
        videoFrame.innerHTML = url ? toEmbed(url) : '';
        videoModal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeVideo() {
        if (!videoModal) return;
        videoFrame.innerHTML = '';
        videoModal.hidden = true;
        document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-video-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const card = btn.closest('[data-video-url]');
            openVideo(card ? card.dataset.videoUrl : '');
        });
    });

    if (videoClose) videoClose.addEventListener('click', closeVideo);

    // ═══════════════════════════════════════════
    // 4. RECENT EVENTS SLIDER
    // ═══════════════════════════════════════════
    const slider = document.querySelector('[data-events-slider]');
    if (slider) {
        const track = slider.querySelector('.recent-events__track');
        const prevBtn = slider.querySelector('[data-events-prev]');
        const nextBtn = slider.querySelector('[data-events-next]');
        const cards = Array.from(track.querySelectorAll('.event-card'));

        let currentIndex = 0;

        function getVisibleCards() {
            const width = window.innerWidth;
            if (width < 640) return 1;
            if (width < 991) return 2;
            return 3;
        }

        function updateSlider() {
            const cardWidth = cards[0].offsetWidth + 20;
            track.style.transform = 'translateX(-' + currentIndex * cardWidth + 'px)';
        }

        if (prevBtn) prevBtn.addEventListener('click', function () {
            if (currentIndex > 0) currentIndex--;
            updateSlider();
        });

        if (nextBtn) nextBtn.addEventListener('click', function () {
            const visible = getVisibleCards();
            if (currentIndex < cards.length - visible) currentIndex++;
            updateSlider();
        });

        window.addEventListener('resize', function () {
            currentIndex = 0;
            updateSlider();
        });
    }

    // ═══════════════════════════════════════════
    // 5. KEYBOARD + BACKDROP CLOSE
    // ═══════════════════════════════════════════
    document.addEventListener('keydown', function (e) {
        if (!lightbox.hidden && (e.key === 'Escape')) closeLightbox();
        if (!lightbox.hidden && e.key === 'ArrowLeft') openLightbox(lbIndex - 1);
        if (!lightbox.hidden && e.key === 'ArrowRight') openLightbox(lbIndex + 1);
        if (!videoModal.hidden && e.key === 'Escape') closeVideo();
    });

    if (lightbox) lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) closeLightbox();
    });
    if (videoModal) videoModal.addEventListener('click', function (e) {
        if (e.target === videoModal) closeVideo();
    });

});
