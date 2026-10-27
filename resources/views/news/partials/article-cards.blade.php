@foreach($articles as $article)
    @php
        $articleImageUrl = $article->featuredImageAsset?->url ?: $article->featured_image_url;
        $fallbackImageUrl = cached_asset('assets/images/edutainment/international-students-university-campus-1.jpg');
        $hasArticleImage = filled($articleImageUrl);
        $imageSrc = $hasArticleImage
            ? mlp_image_url($articleImageUrl, ['w' => 480])
            : $fallbackImageUrl;
        $imageAlt = $hasArticleImage
            ? ($article->featured_image_alt ?: $article->featuredImageAsset?->alt ?: $article->title)
            : 'Students walking together on campus';
    @endphp
    <article class="news-row news-row--listing">
        <a href="{{ route('insights.show', $article->slug) }}"
           class="news-row__image"
           aria-label="Read: {{ $article->title }}">
            <img src="{{ $imageSrc }}"
                 alt="{{ $imageAlt }}"
                 width="480" height="300"
                 loading="lazy" decoding="async"
                 data-news-thumbnail
                 data-fallback-src="{{ $fallbackImageUrl }}"
                 data-fallback-alt="Students walking together on campus">
        </a>
        <div class="news-row__content">
            <div class="news-row__meta">
                @if($article->published_at)
                    <time class="news-row__date" datetime="{{ $article->published_at }}">
                        {{ \Carbon\Carbon::parse($article->published_at)->format('M d, Y') }}
                    </time>
                    <span class="news-row__divider" aria-hidden="true">|</span>
                @endif
                <span class="news-row__author">
                    {{ $article->author_name ?? 'Maverick Business Academy' }}
                </span>
                @if($article->reading_time_minutes)
                    <span class="news-row__divider" aria-hidden="true">|</span>
                    <span class="news-row__read-time">
                        {{ $article->reading_time_minutes }} min read
                    </span>
                @endif
            </div>
            <h3 class="news-row__title">
                <a href="{{ route('insights.show', $article->slug) }}">
                    {{ $article->title }}
                </a>
            </h3>
            @if(!empty($article->excerpt))
                <p class="news-row__excerpt">{{ $article->excerpt }}</p>
            @endif
        </div>
    </article>
@endforeach
