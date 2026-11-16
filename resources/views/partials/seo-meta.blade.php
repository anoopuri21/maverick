{{-- ============================================================
     SEO META TAGS — reusable partial
     Expects: $seo (App\Models\SeoMetadata|null)
     Renders Basic SEO, Open Graph, Twitter, Schema and custom scripts
     only when values exist (falls back to sensible defaults).
     ============================================================ --}}
@php
    $seo             = $seo ?? null;
    $metaTitle       = isset($fallbackTitle) && blank($seo->meta_title ?? null) ? $fallbackTitle : ($seo->meta_title ?? null);
    $metaDescription = isset($fallbackDescription) && blank($seo->meta_description ?? null) ? $fallbackDescription : ($seo->meta_description ?? 'Maverick Business Academy - Transforming Learners into Global Leaders.');
    // The layout owns the description when the page defines this section.
    if (View::hasSection('meta_description')) {
        $sectionDescription = View::yieldContent('meta_description');
        $metaDescription = filled($sectionDescription) ? $sectionDescription : 'Maverick Business Academy - Transforming Learners into Global Leaders.';
    }
    $canonical       = \App\Support\PreferredOrigin::normalize($seo->canonical_url ?? null, request());
    $ogTitle         = isset($fallbackTitle) && blank($seo->og_title ?? null) ? $metaTitle : ($seo->og_title ?? $metaTitle);
    $ogDescription   = filled($seo->og_description ?? null) ? $seo->og_description : $metaDescription;
    $ogImage         = settings_media_url($seo, 'og_image_url');
    $ogType          = $seo->og_type ?? 'website';
    $twitterCard     = $seo->twitter_card ?? 'summary_large_image';
    $twitterTitle    = isset($fallbackTitle) && blank($seo->twitter_title ?? null) ? $ogTitle : ($seo->twitter_title ?? $ogTitle);
    $twitterDesc     = filled($seo->twitter_description ?? null) ? $seo->twitter_description : $ogDescription;
    $twitterImage    = settings_media_url($seo, 'twitter_image_url') ?: $ogImage;
    $ogUrl           = $canonical ?: \App\Support\PreferredOrigin::normalize(url()->current(), request());
@endphp

@if($metaTitle)
    <meta name="title" content="{{ $metaTitle }}">
@endif

@if($metaDescription && !View::hasSection('meta_description'))
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
@php
    $safeSchemaJson = \App\Support\SchemaJson::encode($seo->schema_json ?? null);
    if (filled($seo->schema_json ?? null) && $safeSchemaJson === null) {
        logger()->warning('Invalid SEO schema JSON omitted.', ['route' => request()->route()?->getName()]);
    }
@endphp
@if($safeSchemaJson !== null)
    <script type="application/ld+json">{!! $safeSchemaJson !!}</script>
@endif

{{-- Custom head scripts --}}
@if(!empty($seo->custom_head_scripts))
    {!! $seo->custom_head_scripts !!}
@endif
