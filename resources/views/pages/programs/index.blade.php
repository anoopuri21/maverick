@extends('layouts.app')

@section('title', 'Programmes | Maverick Business Academy')
@section('meta_description', 'Explore Maverick Business Academy programmes — Bachelors, Masters, MBA, Diplomas and professional courses.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/program-listing.css') }}">
@endpush

@section('content')
<div class="page-pl">

    <section class="pl-hero" aria-label="Programmes" data-testid="pl-hero">
        <div class="container pl-hero__inner">
            <span class="pl-hero__eyebrow">MAVERICK PROGRAMMES</span>
            <h1 class="pl-hero__title">Explore Your <em>Programme</em></h1>
            <p class="pl-hero__sub">Globally recognised qualifications designed to move your career forward.</p>
        </div>
    </section>

    <section class="pl-list section--light" aria-label="Programme list">
        <div class="container">
            @if($categories->count())
                <div class="pl-filter" role="group" aria-label="Filter programmes">
                    <button type="button" class="pl-filter__btn is-active" data-filter="all">All</button>
                    @foreach($categories as $cat)
                        <button type="button" class="pl-filter__btn" data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
                    @endforeach
                </div>
            @endif

            <div class="pl-grid" data-pl-grid>
                @forelse($programs as $program)
                    @php $catSlug = $program->programCategory->slug ?? 'all'; @endphp
                    <a href="{{ route('programs.show', $program->slug) }}" class="pl-card" data-category="{{ $catSlug }}">
                        @if($program->image_url)
                            <div class="pl-card__media">
                                <img src="{{ $program->image_url }}" alt="{{ $program->title }}" loading="lazy" width="800" height="540">
                            </div>
                        @endif
                        <div class="pl-card__body">
                            @if($program->partner_university)<span class="pl-card__uni">{{ $program->partner_university }}</span>@endif
                            <h2 class="pl-card__title">{{ $program->title }}</h2>
                            @if($program->duration)<span class="pl-card__meta">{{ $program->duration }}</span>@endif
                            @if($program->short_description)<p class="pl-card__desc">{{ $program->short_description }}</p>@endif
                            <span class="pl-card__link">View Programme <span aria-hidden="true">→</span></span>
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
    const btns = document.querySelectorAll('.pl-filter__btn');
    const cards = document.querySelectorAll('.pl-card');
    btns.forEach(btn => btn.addEventListener('click', () => {
        btns.forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');
        const f = btn.getAttribute('data-filter');
        cards.forEach(c => c.style.display = (f === 'all' || c.getAttribute('data-category') === f) ? '' : 'none');
    }));
    if (typeof AnimationUtils !== 'undefined' && typeof gsap !== 'undefined' && !AnimationUtils.prefersReducedMotion) {
        AnimationUtils.fadeUp('.pl-card', { stagger: 0.06 });
    }
});
</script>
@endpush
