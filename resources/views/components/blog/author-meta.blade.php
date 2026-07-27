@props(['post'])

<div class="author-meta">
@if($post->author_avatar_url)    
    <img class="author-meta__avatar"
         src="{{ $post->author_avatar_url }}"
         alt="{{ $post->author_name }}"
         width="40"
         height="40"
         loading="lazy" />
@endif
    <div class="author-meta__info">
        <span class="author-meta__name">{{ $post->author_name }}</span>
        <div class="author-meta__sub">
            <time class="author-meta__date" datetime="{{ $post->published_at }}">
                {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}
            </time>
            <span class="author-meta__divider">•</span>
            <span class="author-meta__reading-time">{{ $post->reading_time_minutes }} min read</span>
        </div>
    </div>
</div>
