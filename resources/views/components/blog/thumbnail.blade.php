@props(['post', 'aspect' => '16/9', 'imgId' => null])

@php
    $seed = crc32($post->slug);
    $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
    $variant = $variants[$seed % count($variants)];
    $initial = strtoupper(mb_substr($post->title, 0, 20));
@endphp

@if($post->hasImage())
    <div class="blog-thumb" style="aspect-ratio: '16/9'">
        <img
            @if($imgId) id="{{ $imgId }}"@endif
            src="{{ $post->featured_image_url }}"
            alt="{{ $post->featured_image_alt ?? $post->title }}"
            loading="lazy"
            width="800" height="450"
        />
    </div>
@else
    <div class="blog-thumb blog-thumb--fallback {{ $variant }}" style="aspect-ratio: '16/9'">
        <span class="blog-thumb__pattern" aria-hidden="true"></span>
        <span class="blog-thumb__initial" aria-hidden="true">{{ $initial }}</span>
        <!-- <span class="blog-thumb__category">{{ $post->category }}</span> -->
    </div>
@endif
