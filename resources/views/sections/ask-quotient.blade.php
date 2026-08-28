@php
    $askQuotient = $askQuotient ?? null;
    $askKeywords = function (?string $raw): string {
        $parts = preg_split('/\s*[·•|]\s*/u', (string) $raw) ?: [];
        $parts = array_values(array_filter(array_map('trim', $parts)));

        return collect($parts)->map(fn ($p) => e($p))->implode(' <span aria-hidden="true">·</span> ');
    };
@endphp
<section id="ask-quotient" class="askq section-wrapper section--light" aria-labelledby="ask-quotient-heading">
  <div class="container">
    <div class="askq__intro">
      @if(filled($askQuotient->label ?? null))
      <div class="section-label">
        <span>{{ $askQuotient->label }}</span>
      </div>
      @endif

      <h2 id="ask-quotient-heading" class="askq__heading section-title">
        <span class="askq__heading-line">{{ $askQuotient->heading ?? '' }}</span>
      </h2>

      @if(html_filled($askQuotient->description ?? null))
      <div class="askq__description body-text">
        {!! rich_html($askQuotient->description ?? null) !!}
      </div>
      @endif
    </div>

    <div class="askq__framework">
      <ol class="askq__cards" aria-label="The three parts of the ASK Quotient">
        <li class="askq__card" data-ask-card="attitude">
          <article class="askq__card-inner" aria-labelledby="ask-attitude-heading">
            <div class="askq__card-meta">
              <span class="askq__card-letter" aria-hidden="true">{{ $askQuotient->card_a_letter ?? '' }}</span>
            </div>
            <h3 id="ask-attitude-heading" class="askq__card-heading">{{ $askQuotient->card_a_heading ?? '' }}</h3>
            <p class="askq__card-keywords">{!! $askKeywords($askQuotient->card_a_keywords ?? null) !!}</p>
            <div class="askq__card-definition">{!! rich_html($askQuotient->card_a_definition ?? null) !!}</div>
          </article>
        </li>

        <li class="askq__card" data-ask-card="skills">
          <article class="askq__card-inner" aria-labelledby="ask-skills-heading">
            <div class="askq__card-meta">
              <span class="askq__card-letter" aria-hidden="true">{{ $askQuotient->card_s_letter ?? '' }}</span>
            </div>
            <h3 id="ask-skills-heading" class="askq__card-heading">{{ $askQuotient->card_s_heading ?? '' }}</h3>
            <p class="askq__card-keywords">{!! $askKeywords($askQuotient->card_s_keywords ?? null) !!}</p>
            <div class="askq__card-definition">{!! rich_html($askQuotient->card_s_definition ?? null) !!}</div>
          </article>
        </li>

        <li class="askq__card" data-ask-card="knowledge">
          <article class="askq__card-inner" aria-labelledby="ask-knowledge-heading">
            <div class="askq__card-meta">
              <span class="askq__card-letter" aria-hidden="true">{{ $askQuotient->card_k_letter ?? '' }}</span>
            </div>
            <h3 id="ask-knowledge-heading" class="askq__card-heading">{{ $askQuotient->card_k_heading ?? '' }}</h3>
            <p class="askq__card-keywords">{!! $askKeywords($askQuotient->card_k_keywords ?? null) !!}</p>
            <div class="askq__card-definition">{!! rich_html($askQuotient->card_k_definition ?? null) !!}</div>
          </article>
        </li>
      </ol>
    </div>
  </div>
</section>
