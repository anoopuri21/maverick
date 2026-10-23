document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('events-grid');
    const loader = document.getElementById('events-loader');
    const sentinel = document.getElementById('events-sentinel');

    if (!grid || !sentinel) return;

    let isLoading = false;

    const loadMoreEvents = async () => {
        if (isLoading) return;

        const hasMore = grid.getAttribute('data-has-more') === 'true';
        if (!hasMore) return;

        const offset = grid.getAttribute('data-next-offset') || '10';
        isLoading = true;

        if (loader) loader.style.display = 'flex';

        try {
            const response = await fetch(`/events?offset=${offset}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load events');
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
            console.error('Error auto-loading events:', error);
        } finally {
            isLoading = false;
            if (loader) loader.style.display = 'none';
        }
    };

    // IntersectionObserver to auto-load 10 events before user hits the very bottom
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                loadMoreEvents();
            }
        });
    }, {
        root: null,
        rootMargin: '300px 0px', // trigger 300px before reaches bottom
        threshold: 0.1
    });

    observer.observe(sentinel);
});
