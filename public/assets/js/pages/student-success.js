document.addEventListener('DOMContentLoaded', function () {
    function bindLoadMore(key) {
        const btn = document.querySelector('[data-load-more="' + key + '"]');
        const grid = document.querySelector('[data-success-grid="' + key + '"]');
        if (!btn || !grid) return;

        btn.addEventListener('click', function () {
            if (btn.disabled) return;
            btn.disabled = true;

            const url = new URL(btn.getAttribute('data-url'), window.location.origin);
            url.searchParams.set('offset', btn.getAttribute('data-offset') || '0');

            fetch(url.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.html) {
                        grid.insertAdjacentHTML('beforeend', data.html);
                    }
                    btn.setAttribute('data-offset', String(data.next_offset || 0));
                    if (!data.has_more) {
                        const wrap = btn.closest('[data-load-more-wrap]');
                        if (wrap) wrap.style.display = 'none';
                    }
                })
                .finally(function () {
                    btn.disabled = false;
                });
        });
    }

    bindLoadMore('stories');
    bindLoadMore('videos');

    const modal = document.getElementById('successVideoModal');
    const frame = modal ? modal.querySelector('[data-video-frame]') : null;
    const closeBtn = modal ? modal.querySelector('[data-video-close]') : null;
    let lastFocus = null;

    function closeVideo() {
        if (!modal || !frame) return;
        frame.innerHTML = '';
        modal.hidden = true;
        document.body.style.overflow = '';
        if (lastFocus) lastFocus.focus();
    }

    function openVideo(src, title) {
        if (!modal || !frame || !src) return;
        lastFocus = document.activeElement;
        const iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.title = title || 'Video success story';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;
        frame.innerHTML = '';
        frame.appendChild(iframe);
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        if (closeBtn) closeBtn.focus();
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-video-open]');
        if (!btn) return;
        openVideo(btn.getAttribute('data-video-embed'), btn.getAttribute('aria-label'));
    });

    if (closeBtn) closeBtn.addEventListener('click', closeVideo);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeVideo();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && !modal.hidden) closeVideo();
    });
});
