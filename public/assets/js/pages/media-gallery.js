// ============================================
// MEDIA GALLERY — photo/video load-more,
// lightbox + video modal
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════════════
    // 1. PHOTOS — show 15 on load, +10 per click
    // ═══════════════════════════════════════════
    const masonry = document.querySelector('[data-masonry]');
    const galleryItems = masonry ? Array.from(masonry.querySelectorAll('.gallery-item')) : [];
    const photoLoadBtn = document.querySelector('[data-load-more="photos"]');

    const PHOTO_INITIAL = 15;
    const PHOTO_BATCH = 10;
    let photoLoaded = Math.min(PHOTO_INITIAL, galleryItems.length);

    function refreshPhotos() {
        galleryItems.forEach(function (item, i) {
            item.classList.toggle('is-hidden', i >= photoLoaded);
        });

        if (photoLoadBtn) {
            photoLoadBtn.closest('[data-load-more-wrap]').style.display =
                photoLoaded >= galleryItems.length ? 'none' : '';
        }
    }

    if (photoLoadBtn) {
        photoLoadBtn.addEventListener('click', function () {
            photoLoaded = Math.min(photoLoaded + PHOTO_BATCH, galleryItems.length);
            refreshPhotos();
        });
    }

    if (galleryItems.length) {
        refreshPhotos();
    }

    // ═══════════════════════════════════════════
    // 2. VIDEOS — show 6 on load, +6 per click
    // ═══════════════════════════════════════════
    const videoItems = Array.from(document.querySelectorAll('[data-video-item]'));
    const videoLoadBtn = document.querySelector('[data-load-more="videos"]');

    const VIDEO_INITIAL = 6;
    const VIDEO_BATCH = 6;
    let videoLoaded = Math.min(VIDEO_INITIAL, videoItems.length);

    function refreshVideos() {
        videoItems.forEach(function (item, i) {
            item.classList.toggle('is-hidden', i >= videoLoaded);
        });

        if (videoLoadBtn) {
            videoLoadBtn.closest('[data-load-more-wrap]').style.display =
                videoLoaded >= videoItems.length ? 'none' : '';
        }
    }

    if (videoLoadBtn) {
        videoLoadBtn.addEventListener('click', function () {
            videoLoaded = Math.min(videoLoaded + VIDEO_BATCH, videoItems.length);
            refreshVideos();
        });
    }

    if (videoItems.length) {
        refreshVideos();
    }

    // ═══════════════════════════════════════════
    // 3. LIGHTBOX
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
    // 4. VIDEO MODAL
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
