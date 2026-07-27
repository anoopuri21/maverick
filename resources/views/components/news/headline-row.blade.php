@props(['post'])

<div class="news-row">
    <div class="news-row__thumb">
        <x-news.thumbnail :post="$post" aspect="140/100" />
    </div>
    <div class="news-row__content">
        <div class="news-row__header">
            <span class="news-row__date">
                {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y') }}
            </span>
            <span class="news-ticker__divider" aria-hidden="true">|</span>
            <span class="news-row__author" style="font-size: var(--font-size-xs); color: var(--color-text-muted);">
                {{ $post->author_name }}
            </span>
        </div>
        <h3 class="news-row__title">
            <a href="{{ route('insights.show', $post->slug) }}" class="news-row__title-link">
                {{ $post->title }}
            </a>
        </h3>
        <p class="news-row__excerpt">{{ $post->excerpt }}</p>
    </div>
</div>
