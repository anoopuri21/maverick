@props(['post'])

<article class="blog-card fade-up">
    <div class="blog-card__image-wrapper">
        <x-blog.thumbnail :post="$post" aspect="16/10" />
        <span class="blog-card__badge">
            <x-blog.category-pill :category="'Blog'" />
        </span>
    </div>
    <div class="blog-card__content">
        <h3 class="blog-card__title">
            <a href="{{ route('insights.show', $post->slug) }}" class="blog-card__title-link">
                {{ $post->title }}
            </a>
        </h3>
        <p class="blog-card__excerpt">{{ $post->excerpt }}</p>

        <x-blog.author-meta :post="$post" />

        <div class="blog-card__footer">
            <a href="{{ route('insights.show', $post->slug) }}" class="blog-card__cta">
                Read Article
                <span class="blog-card__cta-arrow">→</span>
            </a>
        </div>
    </div>
</article>
