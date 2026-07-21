<section id="upcoming-events" class="events section-wrapper section--dark" aria-label="Upcoming Events">
  <div class="container events__inner">
    <div class="events__header">
      <div class="section-label"><span>Upcoming Events</span></div>
      <h2 class="events__heading section-title">
        <span class="events__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Learn Beyond</span>
          </span>
        </span>
        <span class="events__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">The Classroom</span>
          </span>
        </span>
      </h2>
      <p class="events__subtitle body-text">
        Workshops, masterclasses, and graduation moments — join our global
        learning community
      </p>
    </div>

    <div class="scroll-row scroll-row--dark" data-scroll-row>
      <button class="scroll-row__btn scroll-row__btn--prev" aria-label="Scroll left" data-scroll-prev>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 18l-6-6 6-6" />
        </svg>
      </button>
      <div class="events__scroll" data-scroll-container data-lenis-prevent>
        <div class="events__track">
          @forelse($events as $event)
            <article class="events__card fade-up">
              <div class="events__card-date">
                <span class="events__date-day">{{ $event->event_date->format('d') }}</span>
                <span class="events__date-month">{{ strtoupper($event->event_date->format('M Y')) }}</span>
              </div>
              <div style="display:flex; flex-wrap:wrap; height:100%; align-content:space-between;">
                <h3 class="events__card-title">{{ $event->title }}</h3>
                @if($event->description)
                  <p class="events__card-desc">{{ $event->description }}</p>
                @endif
              </div>
            </article>
          @empty
            <p class="body-text" style="padding: 2rem; color: #fff;">No upcoming events.</p>
          @endforelse
        </div>
      </div>
      <button class="scroll-row__btn scroll-row__btn--next" aria-label="Scroll right" data-scroll-next>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 18l6-6-6-6" />
        </svg>
      </button>
    </div>
  </div>
</section>
