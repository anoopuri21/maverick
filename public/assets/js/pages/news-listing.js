(() => {
    'use strict';

    function initThumbnailFallbacks() {
        const useFallback = (image) => {
            const fallbackSrc = image.dataset.fallbackSrc;
            image.closest('.news-row__image')?.classList.add('news-row__image--fallback');

            if (!fallbackSrc || image.getAttribute('src') === fallbackSrc || image.dataset.fallbackApplied === 'true') {
                return;
            }

            image.dataset.fallbackApplied = 'true';
            image.src = fallbackSrc;
            image.alt = image.dataset.fallbackAlt || 'Students walking together on campus';
        };

        document.addEventListener('error', (event) => {
            const image = event.target;
            if (image instanceof HTMLImageElement && image.matches('[data-news-thumbnail]')) {
                useFallback(image);
            }
        }, true);

        // A very fast broken URL can fail before this deferred script runs.
        document.querySelectorAll('[data-news-thumbnail]').forEach((image) => {
            if (image.complete && image.naturalWidth === 0) {
                useFallback(image);
            }
        });
    }

    function initTicker() {
        const tickerTrack = document.getElementById('news-ticker-track');
        const tickerWrap = document.getElementById('news-ticker');
        const gsap = window.gsap;

        if (!tickerTrack || !tickerWrap || !gsap || typeof gsap.to !== 'function') {
            return;
        }

        const prefersReducedMotion = window.matchMedia
            && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReducedMotion) {
            gsap.set(tickerTrack, { x: 0 });
            return;
        }

        let tickerTween = null;
        let isHovered = false;

        const startTicker = () => {
            if (tickerTween) {
                return;
            }

            const totalWidth = tickerTrack.scrollWidth / 2;
            if (!totalWidth) {
                return;
            }

            tickerTween = gsap.to(tickerTrack, {
                x: -totalWidth,
                duration: 40,
                ease: 'none',
                repeat: -1,
            });

            if (document.hidden || isHovered) {
                tickerTween.pause();
            }
        };

        tickerWrap.addEventListener('mouseenter', () => {
            isHovered = true;
            tickerTween?.pause();
        });
        tickerWrap.addEventListener('mouseleave', () => {
            isHovered = false;
            if (!document.hidden) {
                startTicker();
                tickerTween?.play();
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                tickerTween?.pause();
            } else if (!isHovered) {
                startTicker();
                tickerTween?.play();
            }
        });

        startTicker();
    }

    function initAutomaticArticleLoading() {
        const articleList = document.querySelector('[data-news-list]');
        const sentinel = document.querySelector('[data-news-sentinel]');
        const nextLink = document.querySelector('[data-news-next]');
        const status = document.querySelector('[data-news-load-status]');
        const loadedLabel = document.querySelector('[data-news-loaded-label]');

        if (!articleList || !sentinel || !nextLink || typeof window.fetch !== 'function') {
            return;
        }

        let nextUrl = articleList.dataset.nextUrl || nextLink.href;
        let loadedCount = Number(articleList.dataset.loadedCount || 0);
        let isLoading = false;
        let observer = null;

        const setStatus = (message) => {
            if (status) {
                status.textContent = message;
            }
        };

        const updateNextLink = () => {
            if (nextUrl) {
                nextLink.href = nextUrl;
                nextLink.removeAttribute('aria-disabled');
                nextLink.removeAttribute('tabindex');
                nextLink.classList.remove('news-pagination__link--disabled');
                return;
            }

            nextLink.removeAttribute('href');
            nextLink.setAttribute('aria-disabled', 'true');
            nextLink.setAttribute('tabindex', '-1');
            nextLink.classList.add('news-pagination__link--disabled');
        };

        const loadNextPage = async (navigateOnFailure = false) => {
            if (isLoading || !nextUrl) {
                return;
            }

            const requestUrl = nextUrl;
            isLoading = true;
            nextLink.setAttribute('aria-busy', 'true');
            setStatus('Loading more articles…');

            try {
                const response = await window.fetch(requestUrl, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`News request failed (${response.status}).`);
                }

                const result = await response.json();
                if (typeof result.html !== 'string' || !Number.isFinite(Number(result.count))) {
                    throw new Error('The news response was incomplete.');
                }

                if (result.html.trim()) {
                    articleList.insertAdjacentHTML('beforeend', result.html);
                }

                const batchCount = Number(result.count);
                loadedCount += batchCount;
                nextUrl = typeof result.next_url === 'string' ? result.next_url : '';
                articleList.dataset.nextUrl = nextUrl;
                articleList.dataset.loadedCount = String(loadedCount);

                if (loadedLabel) {
                    loadedLabel.removeAttribute('aria-current');
                    loadedLabel.textContent = `${loadedCount} articles loaded`;
                }

                updateNextLink();

                if (nextUrl) {
                    setStatus(`${batchCount} more articles loaded.`);
                    if (observer) {
                        observer.unobserve(sentinel);
                        observer.observe(sentinel);
                    }
                } else {
                    setStatus(`All ${loadedCount} articles are loaded.`);
                    observer?.disconnect();
                    sentinel.remove();
                }
            } catch (error) {
                setStatus('Automatic loading failed. Select “Older articles” to open the next page.');
                if (navigateOnFailure) {
                    window.location.assign(requestUrl);
                }
            } finally {
                isLoading = false;
                nextLink.removeAttribute('aria-busy');
            }
        };

        nextLink.addEventListener('click', (event) => {
            if (!nextUrl || isLoading) {
                event.preventDefault();
                return;
            }

            event.preventDefault();
            loadNextPage(true);
        });

        if ('IntersectionObserver' in window) {
            observer = new IntersectionObserver((entries) => {
                if (entries.some((entry) => entry.isIntersecting)) {
                    loadNextPage();
                }
            }, { rootMargin: '700px 0px' });
            observer.observe(sentinel);
        }
    }

    function init() {
        initThumbnailFallbacks();
        initTicker();
        initAutomaticArticleLoading();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
