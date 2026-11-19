document.addEventListener('DOMContentLoaded', function () {
    function bindLoadMore(key) {
        const btn = document.querySelector('[data-load-more="' + key + '"]');
        const grid = document.querySelector('[data-success-grid="' + key + '"]');
        if (!btn || !grid) return;

        btn.addEventListener('click', function () {
            if (btn.disabled) return;

            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');

            const url = new URL(btn.getAttribute('data-url'), window.location.origin);
            url.searchParams.set('offset', btn.getAttribute('data-offset') || '0');

            fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    if (!response.ok) throw new Error('Student success content could not be loaded.');
                    return response.json();
                })
                .then(function (data) {
                    if (typeof data.html === 'string' && data.html !== '') {
                        grid.insertAdjacentHTML('beforeend', data.html);
                    }

                    btn.setAttribute('data-offset', String(data.next_offset || 0));
                    if (!data.has_more) {
                        const wrap = btn.closest('[data-load-more-wrap]');
                        if (wrap) wrap.style.display = 'none';
                    }
                })
                .catch(function (error) {
                    console.error('Student success load-more failed:', error);
                })
                .finally(function () {
                    btn.disabled = false;
                    btn.removeAttribute('aria-busy');
                });
        });
    }

    bindLoadMore('stories');
    bindLoadMore('videos');

    const storyModal = document.getElementById('studentStoryModal');
    const storyPanel = storyModal ? storyModal.querySelector('.ep-story-modal__panel') : null;
    const storyClose = storyModal ? storyModal.querySelector('.ep-story-modal__close') : null;
    const storyPhoto = storyModal ? storyModal.querySelector('[data-story-modal-photo]') : null;
    const storyFallback = storyModal ? storyModal.querySelector('[data-story-modal-fallback]') : null;
    const videoModal = document.getElementById('successVideoModal');
    const videoFrame = videoModal ? videoModal.querySelector('[data-video-frame]') : null;
    const videoClose = videoModal ? videoModal.querySelector('[data-video-close]') : null;

    let storyLastFocus = null;
    let videoLastFocus = null;
    let bodyOverflowBeforeStory = '';
    let bodyOverflowBeforeVideo = '';

    function setStoryText(selector, value, fallback) {
        const element = storyModal ? storyModal.querySelector(selector) : null;
        if (element) element.textContent = value || fallback || '';
    }

    function openStory(trigger) {
        if (!storyModal || !trigger) return;

        storyLastFocus = document.activeElement;
        bodyOverflowBeforeStory = document.body.style.overflow;

        const name = trigger.dataset.storyName || 'Student story';
        const photo = trigger.dataset.storyPhoto || '';
        setStoryText('[data-story-modal-name]', name, 'Student story');
        setStoryText('[data-story-modal-university]', trigger.dataset.storyUniversity, 'Not specified');
        setStoryText('[data-story-modal-course]', trigger.dataset.storyCourse, 'Not specified');
        setStoryText('[data-story-modal-nationality]', trigger.dataset.storyNationality, 'Not specified');
        setStoryText('[data-story-modal-quote]', trigger.dataset.storyQuote, 'No description provided.');

        if (storyPhoto && storyFallback) {
            if (photo) {
                storyPhoto.src = photo;
                storyPhoto.alt = name;
                storyPhoto.hidden = false;
                storyFallback.hidden = true;
            } else {
                storyPhoto.removeAttribute('src');
                storyPhoto.alt = '';
                storyPhoto.hidden = true;
                storyFallback.textContent = name
                    .split(/\s+/)
                    .filter(Boolean)
                    .slice(0, 2)
                    .map(function (part) { return part.charAt(0); })
                    .join('')
                    .toUpperCase();
                storyFallback.hidden = false;
            }
        }

        storyModal.hidden = false;
        document.body.style.overflow = 'hidden';
        if (storyClose) storyClose.focus();
    }

    function closeStory() {
        if (!storyModal || storyModal.hidden) return;

        storyModal.hidden = true;
        document.body.style.overflow = bodyOverflowBeforeStory;
        if (storyPhoto) storyPhoto.removeAttribute('src');

        if (storyLastFocus && storyLastFocus.isConnected) storyLastFocus.focus();
        storyLastFocus = null;
    }

    function trapStoryFocus(event) {
        if (!storyPanel) return;

        const focusable = Array.from(storyPanel.querySelectorAll(
            'button:not([disabled]), a[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), summary, [tabindex]:not([tabindex="-1"])'
        )).filter(function (element) {
            return !element.hidden && element.getClientRects().length > 0;
        });

        if (focusable.length === 0) {
            event.preventDefault();
            storyModal.focus();
            return;
        }

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    }

    function closeVideo() {
        if (!videoModal || !videoFrame || videoModal.hidden) return;

        videoFrame.replaceChildren();
        videoModal.hidden = true;
        document.body.style.overflow = bodyOverflowBeforeVideo;
        if (videoLastFocus && videoLastFocus.isConnected) videoLastFocus.focus();
        videoLastFocus = null;
    }

    function openVideo(src, title) {
        if (!videoModal || !videoFrame || !src) return;

        videoLastFocus = document.activeElement;
        bodyOverflowBeforeVideo = document.body.style.overflow;

        const iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.title = title || 'Video success story';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;
        videoFrame.replaceChildren(iframe);
        videoModal.hidden = false;
        document.body.style.overflow = 'hidden';
        if (videoClose) videoClose.focus();
    }

    if (videoClose) videoClose.addEventListener('click', closeVideo);
    if (videoModal) {
        videoModal.addEventListener('click', function (event) {
            if (event.target === videoModal) closeVideo();
        });
    }

    document.addEventListener('click', function (event) {
        const target = event.target instanceof Element ? event.target : null;
        if (!target) return;

        const closeControl = target.closest('[data-story-close]');
        if (closeControl && storyModal && storyModal.contains(closeControl)) {
            closeStory();
            return;
        }

        const storyTrigger = target.closest('[data-story-open]');
        if (storyTrigger) {
            openStory(storyTrigger);
            return;
        }

        const videoTrigger = target.closest('[data-video-open]');
        if (videoTrigger) {
            openVideo(videoTrigger.getAttribute('data-video-embed'), videoTrigger.getAttribute('aria-label'));
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            if (storyModal && !storyModal.hidden) {
                closeStory();
                return;
            }
            if (videoModal && !videoModal.hidden) closeVideo();
        }

        if (event.key === 'Tab' && storyModal && !storyModal.hidden) {
            trapStoryFocus(event);
        }
    });
});
