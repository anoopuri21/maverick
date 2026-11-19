@props(['post'])

<div class="author-meta">
    @if($url = mlp_image_url($post->author_avatar_url ?? null, ['w' => 80]))
        <img class="author-meta__avatar"
             src="{{ $url }}"
             alt="{{ $post->author_name }}"
             width="40"
             height="40"
             loading="lazy"
             decoding="async" />
    @endif
    <div class="author-meta__info">
        <span class="author-meta__name">{{ $post->author_name ?? 'Maverick Business Academy' }}</span>
        <div class="author-meta__sub">
            @if($post->published_at)
                <time class="author-meta__date" datetime="{{ $post->published_at }}">
                    {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}
                </time>
            @endif
            @if($post->published_at && $post->reading_time_minutes)
                <span class="author-meta__divider" aria-hidden="true">•</span>
            @endif
            @if($post->reading_time_minutes)
                <span class="author-meta__reading-time">{{ $post->reading_time_minutes }} min read</span>
            @endif
        </div>
    </div>
</div>
