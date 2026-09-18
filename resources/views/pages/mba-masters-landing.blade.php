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
@endpush

@section('content')
<div class="mlp-page mlp-page--polished" id="mlpPage">
  {{-- PDF Section 1: HERO --}}
  @include('pages.mba-masters-landing.hero')
  {{-- PDF Section 2: TRUST --}}
  @include('pages.mba-masters-landing.trust')
  {{-- PDF Section 3: OVERVIEW --}}
  @include('pages.mba-masters-landing.overview')
  {{-- PDF Section 4: WHY --}}
  @include('pages.mba-masters-landing.why')
  {{-- PDF Section 5: JOURNEY [NEW - Enabled] --}}
  @include('pages.mba-masters-landing.journey')
  {{-- PDF Section 6: MBA CATEGORIES --}}
  @include('pages.mba-masters-landing.mba')
  {{-- PDF Section 7: MASTERS CATEGORIES --}}
  @include('pages.mba-masters-landing.masters')
  {{-- PDF Section 8: CLASS OF 2025 --}}
  @include('pages.mba-masters-landing.class-2025')
  {{-- Extra: Accreditations (social proof, not in PDF but valuable) --}}
  @include('sections.accreditations')
  {{-- PDF Section 9: COHORT - Your Classmates UAE and GCC [NEW] --}}
  @include('pages.mba-masters-landing.cohort')
  {{-- PDF Section 10: FEES --}}
  @include('pages.mba-masters-landing.fees')
  {{-- PDF Section 11: CAREER STORIES --}}
  @include('pages.mba-masters-landing.career')
  {{-- PDF Section 12: ALUMNI --}}
  @include('pages.mba-masters-landing.alumni')
  {{-- PDF Section 13: PARTNERS --}}
  @include('pages.mba-masters-landing.partners')
  {{-- PDF Section 14: LEARNING [NEW] --}}
  @include('pages.mba-masters-landing.learning')
  {{-- Extra: Video testimonials & proof (not in PDF but keep as social proof) --}}
  @include('pages.mba-masters-landing.video-testimonials')
  @include('pages.mba-masters-landing.video-proof')
  {{-- PDF Section 15: TESTIMONIALS --}}
  @include('pages.mba-masters-landing.testimonials')
  {{-- PDF Section 16: COMPARE [NEW - Enabled] --}}
  @include('pages.mba-masters-landing.compare')
  {{-- PDF Section 17: FAQ --}}
  @include('pages.mba-masters-landing.faq')
  {{-- PDF Section 18: FINAL CTA --}}
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
