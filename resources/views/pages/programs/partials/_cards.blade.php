@php
    $programsListingPage = $programsListingPage ?? safe_settings(\App\Settings\ProgramsListingPageSettings::class);
    $activeCategory = $activeCategory ?? request()->query('category', 'all');
@endphp

<div class="pl-grid" data-pl-grid @isset($nextPage) data-next-page="{{ $nextPage }}" @isset($lastPage) data-last-page="{{ $lastPage }}" @endif>
    @forelse($programs as $program)
        @php
            $catSlug = $program->programCategory?->slug ?? 'all';
            $cardHidden = $activeCategory !== 'all' && $catSlug !== $activeCategory;
        @endphp
        <a href="{{ filled($program->slug) ? route('programs.show', $program->slug) : '#' }}" class="pl-card{{ $cardHidden ? ' is-hidden' : '' }}" data-category="{{ $catSlug }}" @if($cardHidden) hidden @endif @if(! filled($program->slug)) role="group" @endif>
            @if($url = media_url($program->image_url ?? null))
                <div class="pl-card__media">
                    <img src="{{ $url }}" alt="{{ $program->title }}" loading="lazy" decoding="async" width="800" height="540">
                </div>
            @else
                <div class="pl-card__media pl-card__media--empty" aria-hidden="true"></div>
            @endif
            <div class="pl-card__body">
                @if($program->programCategory)<span class="pl-card__cat">{{ $program->programCategory->name }}</span>@endif
                @if($program->universityPartner)<span class="pl-card__uni">{{ $program->universityPartner->name }}</span>@endif
                <h2 class="pl-card__title">{{ $program->title }}</h2>
                @if($program->duration || $program->level)
                    <span class="pl-card__meta">
                        @if($program->duration){{ $program->duration }}@endif
                        @if($program->duration && $program->level)<span class="pl-card__meta-rule">·</span>@endif
                        @if($program->level){{ $program->level }}@endif
                    </span>
                @endif
                @if($program->short_description)<p class="pl-card__desc">{{ $program->short_description }}</p>@endif
                <span class="pl-card__link">{{ $programsListingPage->card_cta_label ?? 'View Programme' }} <span aria-hidden="true">→</span></span>
            </div>
        </a>
    @empty
        <p class="pl-empty">{{ $programsListingPage->empty_message ?? 'Programmes coming soon.' }}</p>
    @endforelse
</div>
