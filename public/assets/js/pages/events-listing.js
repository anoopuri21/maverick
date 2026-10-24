document.addEventListener('DOMContentLoaded', () => {
    const initInfiniteSection = (type) => {
        const grid = document.getElementById(`${type}-events-grid`);
        const loader = document.getElementById(`${type}-events-loader`);
        const sentinel = document.getElementById(`${type}-events-sentinel`);

        if (!grid || !sentinel) return;

        let isLoading = false;

        const loadMore = async () => {
            if (isLoading) return;

            const hasMore = grid.getAttribute('data-has-more') === 'true';
            if (!hasMore) return;

            const offset = grid.getAttribute('data-next-offset') || '10';
            isLoading = true;

            if (loader) loader.style.display = 'flex';

            try {
                const response = await fetch(`/events?type=${type}&offset=${offset}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Failed to load ${type} events`);
                }

                const data = await response.json();

                if (data.html) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;

                    Array.from(tempDiv.children).forEach(child => {
                        grid.appendChild(child);
                    });

                    if (window.lucide && typeof window.lucide.createIcons === 'function') {
                        window.lucide.createIcons();
                    }
                }

                grid.setAttribute('data-next-offset', data.next_offset);
                grid.setAttribute('data-has-more', data.has_more ? 'true' : 'false');

            } catch (error) {
                console.error(`Error auto-loading ${type} events:`, error);
            } finally {
                isLoading = false;
                if (loader) loader.style.display = 'none';
            }
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadMore();
                }
            });
        }, {
            root: null,
            rootMargin: '300px 0px',
            threshold: 0.1
        });

        observer.observe(sentinel);
    };

    initInfiniteSection('upcoming');
    initInfiniteSection('past');
});
