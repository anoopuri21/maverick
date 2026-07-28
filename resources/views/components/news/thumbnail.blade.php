@props(['post', 'aspect' => '16/9', 'imgId' => null])

@php
    $seed = crc32($post->slug);
    $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
    $variant = $variants[$seed % count($variants)];
    $initial = strtoupper(mb_substr($post->title, 0, 1));
@endphp

@if($post->hasImage())
    <div class="news-thumb" style="aspect-ratio: {{ $aspect }}">
        <img
            @if($imgId) id="{{ $imgId }}"@endif
            src="{{ $post->featured_image_url }}"
            alt="{{ $post->featured_image_alt ?? $post->title }}"
            loading="lazy"
        />
        <div class="news-thumb__badge-overlay">
            <span class="news-badge">News</span>
        </div>
    </div>
@else
    <div class="news-thumb news-thumb--fallback {{ $variant }}" style="aspect-ratio: {{ $aspect }}">
        <span class="news-thumb__initial" aria-hidden="true">{{ $initial }}</span>
        <div class="news-thumb__badge-overlay">
            <span class="news-badge">News</span>
        </div>
    </div>
@endif
