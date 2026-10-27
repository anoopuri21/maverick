@props(['post', 'aspect' => '16/9', 'imgId' => null])

@php
    $seed = crc32($post->slug);
    $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
    $variant = $variants[$seed % count($variants)];
    $initial = strtoupper(mb_substr($post->title, 0, 20));
    $imageUrl = $post->resolvedFeaturedImageUrl();
    $imageAlt = $post->resolvedFeaturedImageAlt() ?: $post->title;
@endphp

@if(filled($imageUrl))
    <div class="blog-thumb" style="aspect-ratio: {{ $aspect }}">
        <img
            @if($imgId) id="{{ $imgId }}"@endif
            src="{{ mlp_image_url($imageUrl, ['w' => 800]) }}"
            alt="{{ $imageAlt }}"
            loading="lazy"
            decoding="async"
            width="800" height="450"
        />
    </div>
@else
    <div class="blog-thumb blog-thumb--fallback {{ $variant }}" style="aspect-ratio: {{ $aspect }}">
        <span class="blog-thumb__pattern" aria-hidden="true"></span>
        <span class="blog-thumb__initial" aria-hidden="true">{{ $initial }}</span>
    </div>
@endif
