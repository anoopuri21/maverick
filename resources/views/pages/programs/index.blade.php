@extends('layouts.app')

@section('title', 'Programmes | Maverick Business Academy')
@section('meta_description', 'Explore Maverick Business Academy programmes — Bachelors, Masters, MBA, Diplomas and professional courses, awarded by internationally recognised universities.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/program-listing.css') }}">
@endpush

@section('content')
<div class="page-pl">

    {{-- Hero — Programme Listing --}}
    <section class="pl-hero" aria-label="Programmes" data-testid="pl-hero">
        <div class="container pl-hero__inner">
            <span class="pl-hero__eyebrow">MAVERICK PROGRAMMES</span>
            <h1 class="pl-hero__title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Explore Your</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Programme</em></span></span>
            </h1>
            <p class="pl-hero__sub">
                Globally recognised qualifications across business, management and technology —
                designed to move your career forward.
            </p>
        </div>
    </section>

    {{-- Category filter --}}
    <section class="pl-filter section-wrapper section--light" aria-label="Filter programmes">
        <div class="container">
            <div class="pl-filter__bar" role="group" aria-label="Programme categories">
                <button type="button" class="pl-filter__btn is-active" data-filter="all">All Programmes</button>
                @foreach($categories as $cat)
                    <button type="button" class="pl-filter__btn" data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
                @endforeach
            </div>

            <div class="pl-grid" data-pl-grid>
                @forelse($programs as $program)
                    @php
                        $catSlug = $program->programCategory->slug ?? 'all';
                    @endphp
                    <a href="{{ route('programs.show', $program->slug) }}" class="pl-card" data-category="{{ $catSlug }}">
                        <div class="pl-card__media">
                            <img src="{{ $program->image_url ?? asset('assets/images/homepage/mba.jpg') }}"
                                 alt="{{ $program->title }}" loading="lazy" width="800" height="540">
                            @if($program->level)
                                <span class="pl-card__level">{{ $program->level }}</span>
                            @endif
                        </div>
                        <div class="pl-card__body">
                            @if($program->partner_university)
                                <span class="pl-card__uni">{{ $program->partner_university }}</span>
                            @endif
                            <h2 class="pl-card__title">{{ $program->title }}</h2>
                            <div class="pl-card__meta">
                                @if($program->duration)<span>{{ $program->duration }}</span>@endif
                                @if($program->programCategory)<span>{{ $program->programCategory->name }}</span>@endif
                            </div>
                            @if($program->short_description)
                                <p class="pl-card__desc">{{ $program->short_description }}</p>
                            @endif
                            <span class="pl-card__link">
                                View Programme <span class="inline-icon" data-lucide="arrow-right"></span>
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="body-text" style="padding:2rem;">Programmes coming soon.</p>
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.pl-filter__btn');
    const cards = document.querySelectorAll('.pl-card');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            const filter = btn.getAttribute('data-filter');
            cards.forEach(card => {
                const cat = card.getAttribute('data-category');
                const show = filter === 'all' || cat === filter;
                card.style.display = show ? '' : 'none';
            });
        });
    });

    if (typeof AnimationUtils !== 'undefined' && typeof gsap !== 'undefined' && !AnimationUtils.prefersReducedMotion) {
        AnimationUtils.textReveal('.pl-hero .text-reveal-inner', { stagger: 0.15 });
        AnimationUtils.fadeUp('.pl-card', { stagger: 0.08 });
    }
});
</script>
@endpush
