{{-- ============================================================
     SEO META TAGS — reusable partial
     Expects: $seo (App\Models\SeoMetadata|null)
     Renders Basic SEO, Open Graph, Twitter, Schema and custom scripts
     only when values exist (falls back to sensible defaults).
     ============================================================ --}}
@php
    $metaTitle       = $seo->meta_title ?? null;
    $metaDescription = $seo->meta_description ?? null;
    $canonical       = $seo->canonical_url ?? null;
    $ogTitle         = $seo->og_title ?? $metaTitle;
    $ogDescription   = $seo->og_description ?? $metaDescription;
    $ogImage         = settings_media_url($seo, 'og_image_url');
    $ogType          = $seo->og_type ?? 'website';
    $twitterCard     = $seo->twitter_card ?? 'summary_large_image';
    $twitterTitle    = $seo->twitter_title ?? $ogTitle;
    $twitterDesc     = $seo->twitter_description ?? $ogDescription;
    $twitterImage    = settings_media_url($seo, 'twitter_image_url') ?: $ogImage;
    $ogUrl           = $canonical ?: url()->current();
@endphp

@if($metaTitle)
    <meta name="title" content="{{ $metaTitle }}">
@endif

@if($metaDescription)
    <meta name="description" content="{{ $metaDescription }}">
@endif

@if(!empty($seo->meta_keywords))
    <meta name="keywords" content="{{ $seo->meta_keywords }}">
@endif

<link rel="canonical" href="{{ $ogUrl }}">

@if(!empty($seo->robots) && $seo->robots !== 'index, follow')
    <meta name="robots" content="{{ $seo->robots }}">
@endif

{{-- Open Graph --}}
@if($ogTitle)
    <meta property="og:title" content="{{ $ogTitle }}">
@endif
@if($ogDescription)
    <meta property="og:description" content="{{ $ogDescription }}">
@endif
@if($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:site_name" content="Maverick Business Academy">

{{-- Twitter --}}
<meta name="twitter:card" content="{{ $twitterCard }}">
@if($twitterTitle)
    <meta name="twitter:title" content="{{ $twitterTitle }}">
@endif
@if($twitterDesc)
    <meta name="twitter:description" content="{{ $twitterDesc }}">
@endif
@if($twitterImage)
    <meta name="twitter:image" content="{{ $twitterImage }}">
@endif

{{-- Schema.org JSON-LD --}}
@if(!empty($seo->schema_json))
    <script type="application/ld+json">{!! $seo->schema_json !!}</script>
@endif

{{-- Custom head scripts --}}
@if(!empty($seo->custom_head_scripts))
    {!! $seo->custom_head_scripts !!}
@endif
