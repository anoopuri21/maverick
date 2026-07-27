@props(['post'])

<article class="news-featured">
    <div class="news-featured__grid">
        <div class="news-featured__image-wrapper">
            <x-news.thumbnail :post="$post" aspect="16/10" />
        </div>
        <div class="news-featured__content">
            <div class="news-featured__badge">
                <span class="news-badge">Featured Story</span>
            </div>
            <h2 class="news-featured__title">
                <a href="{{ route('insights.show', $post->slug) }}" class="news-featured__title-link">
                    {{ $post->title }}
                </a>
            </h2>
            <p class="news-featured__excerpt">{{ $post->excerpt }}</p>
            <div class="news-featured__meta">
                <span>By {{ $post->author_name }}</span>
                <span class="news-ticker__divider" aria-hidden="true">|</span>
                <time datetime="{{ $post->published_at }}">
                    {{ \Carbon\Carbon::parse($post->published_at)->format('F d, Y') }}
                </time>
            </div>
        </div>
    </div>
</article>
