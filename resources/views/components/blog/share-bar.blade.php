@props(['url', 'title'])

<div class="blog-share-bar" aria-label="Share this article">
    <span class="blog-share-bar__title">Share</span>
    <div class="blog-share-bar__links">
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($url) }}"
           class="blog-share-bar__link"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Share on LinkedIn"
           title="Share on LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="20" height="20">
                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
            </svg>
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($title) }}"
           class="blog-share-bar__link"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Share on Twitter"
           title="Share on Twitter">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="20" height="20">
                <path d="M18.244 2.25h3.308l-7.227 7.56 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.085L1.254 2.25h6.816l4.7 6.222 5.474-6.222zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/>
            </svg>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
           class="blog-share-bar__link"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Share on Facebook"
           title="Share on Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="20" height="20">
                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
            </svg>
        </a>
        <button class="blog-share-bar__link blog-share-bar__copy-btn"
                data-copy-url="{{ $url }}"
                aria-label="Copy link to clipboard"
                title="Copy Link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
            </svg>
            <span class="blog-share-bar__tooltip">Copied!</span>
        </button>
    </div>
</div>
