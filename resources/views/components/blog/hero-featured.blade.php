@props(['post'])

<article class="blog-hero-featured">
    <div class="blog-hero-featured__image-wrapper">
        <x-blog.thumbnail :post="$post" aspect="16/9" />
    </div>
    <div class="blog-hero-featured__content">
        <div class="blog-hero-featured__tag">
            <span class="blog-tag-eyebrow">Featured Article</span>
            <x-blog.category-pill :category="$post->category" />
        </div>

        <h2 class="blog-hero-featured__title">
            <a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a>
        </h2>

        <p class="blog-hero-featured__excerpt">{{ $post->excerpt }}</p>

        <x-blog.author-meta :post="$post" />

        <div class="blog-hero-featured__footer">
            <a href="{{ route('blogs.show', $post->slug) }}" class="btn btn--primary blog-hero-featured__btn">
                Read Article
            </a>
        </div>
    </div>
</article>
