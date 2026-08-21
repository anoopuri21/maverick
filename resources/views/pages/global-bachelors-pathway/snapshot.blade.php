@php
    $snapshotCards = collect($snapshot->cards ?? [])->filter(fn ($card) => filled($card['title'] ?? null) || collect($card['items'] ?? [])->filter()->isNotEmpty());
    $snapshotCtas = collect($snapshot->ctas ?? [])->filter(fn ($cta) => filled($cta['label'] ?? null) && filled($cta['url'] ?? null));
@endphp
@if($snapshotCards->isNotEmpty() || $snapshotCtas->isNotEmpty())
<section class="gbp-snapshot section-wrapper section--light" aria-label="Programme Snapshot" data-testid="gbp-snapshot">
    <div class="container">
        @if($snapshotCards->isNotEmpty())
        <div class="gbp-snapshot__grid">
            @foreach($snapshotCards as $card)
            <article class="gbp-snapshot-card" data-testid="gbp-snapshot-{{ $loop->iteration }}">
                @if(filled($card['icon_key'] ?? null))
                <div class="gbp-snapshot-card__icon" aria-hidden="true">
                    <x-gbp.icon :name="$card['icon_key']" :size="24" />
                </div>
                @endif
                @if(filled($card['title'] ?? null))
                <h3 class="gbp-snapshot-card__title card-title">{{ $card['title'] }}</h3>
                @endif
                @php $items = collect($card['items'] ?? [])->filter(fn ($item) => filled($item)); @endphp
                @if($items->isNotEmpty())
                <ul class="gbp-snapshot-card__list">
                    @foreach($items as $item)
                    <li>
                        <span aria-hidden="true"><x-gbp.icon name="check" :size="18" /></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                @endif
            </article>
            @endforeach
        </div>
        @endif

        @if($snapshotCtas->isNotEmpty())
        <div class="gbp-snapshot__ctas fade-up">
            @foreach($snapshotCtas as $cta)
            <a href="{{ edu_href($cta['url']) }}" class="{{ ($cta['style'] ?? 'primary') === 'ghost' ? 'btn btn--ghost' : 'btn btn--primary' }}">{{ $cta['label'] }}</a>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
