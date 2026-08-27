@extends('layouts.app')

@section('title', ($seo->meta_title ?? 'Online MBA & Master\'s in UAE | Maverick Business Academy London'))
@section('seo_from_partial')
@endsection

@push('head')
    @include('partials.seo-meta', ['seo' => $seo])

    @php
        $faqSchemaItems = collect($faq->items ?? [])
            ->filter(fn ($item) => filled($item['question'] ?? null))
            ->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => trim(strip_tags((string) ($item['answer'] ?? ''))),
                ],
            ])
            ->values()
            ->all();
    @endphp
    @if(empty($seo->schema_json) && $faqSchemaItems !== [])
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqSchemaItems,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endpush

@if(!empty($seo->custom_body_scripts))
@push('scripts')
    {!! $seo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
<link rel="stylesheet" href="{{ cached_asset('assets/css/pages/mba-masters-landing.css') }}" />
<link rel="stylesheet" href="{{ cached_asset('assets/css/pages/mba-masters-polish.css') }}" />
@endpush

@section('content')
<div class="mlp-page mlp-page--polished" id="mlpPage">
  @include('pages.mba-masters-landing.hero')
  @include('pages.mba-masters-landing.trust')
  @include('pages.mba-masters-landing.overview')
  @include('pages.mba-masters-landing.why')
  {{-- @include('pages.mba-masters-landing.journey') --}}
  @include('pages.mba-masters-landing.mba')
  @include('pages.mba-masters-landing.masters')
  @include('sections.accreditations')
  @include('pages.mba-masters-landing.class-snapshot')
  @include('pages.mba-masters-landing.fees')
  @include('pages.mba-masters-landing.career')
  @include('pages.mba-masters-landing.alumni')
  @include('pages.mba-masters-landing.learning')
  @include('pages.mba-masters-landing.partners')
  @include('pages.mba-masters-landing.video-proof')
  @include('pages.mba-masters-landing.testimonials')
  {{-- @include('pages.mba-masters-landing.compare') --}}
  @include('pages.mba-masters-landing.faq')
  @include('pages.mba-masters-landing.final')
</div>

@php
  $wa = preg_replace('/\D+/', '', $site->whatsapp_number ?? '');
@endphp
<div class="mlp-sticky" id="mlpSticky" aria-label="Quick actions">
  @if(filled($wa))
  <a class="mlp-sticky__btn mlp-sticky__btn--wa" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" aria-label="WhatsApp admissions">WhatsApp</a>
  @endif
  <a class="mlp-sticky__btn mlp-sticky__btn--apply" href="#mlp-enquire">Apply Now</a>
</div>
@endsection

@push('scripts')
<script src="{{ cached_asset('assets/js/pages/mba-masters-landing.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-accreditations.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-hero-assembly.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-trust.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-overview.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-archive.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-video-proof.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-class-topics.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-testimonials.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-closing.js') }}" defer></script>
<script src="{{ cached_asset('assets/js/pages/mba-masters-polish.js') }}" defer></script>
@endpush
