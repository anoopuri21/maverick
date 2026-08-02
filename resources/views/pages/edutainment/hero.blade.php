{{-- ===== S1: HERO — Cinematic Full-Viewport ===== --}}
<section id="edu-hero" class="edu-hero" aria-label="Edutainment Hero">
  <div class="edu-hero__bg" aria-hidden="true">
    <div class="edu-hero__bg-image" style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80')"></div>
    <div class="edu-hero__gradient"></div>
    <div class="edu-hero__noise"></div>
    <div class="edu-hero__shapes">
      <svg class="edu-hero__shape edu-hero__shape--1" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></svg>
      <svg class="edu-hero__shape edu-hero__shape--2" viewBox="0 0 300 300" fill="none"><circle cx="150" cy="150" r="120" stroke="rgba(178,2,2,0.2)" stroke-width="1"/></svg>
      <svg class="edu-hero__shape edu-hero__shape--3" viewBox="0 0 100 100" fill="none"><rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/></svg>
    </div>
    <div class="edu-hero__particles">
      @for($i = 0; $i < 6; $i++)
        <div class="edu-hero__particle"></div>
      @endfor
    </div>
    <div class="edu-hero__scanline"></div>
    <div class="edu-hero__corners">
      <div class="edu-hero__corner edu-hero__corner--tl"></div>
      <div class="edu-hero__corner edu-hero__corner--tr"></div>
      <div class="edu-hero__corner edu-hero__corner--bl"></div>
      <div class="edu-hero__corner edu-hero__corner--br"></div>
    </div>
  </div>

  <div class="edu-hero__content">
    <div class="container">
      <span class="edu-hero__tag fade-up">
        <span class="edu-hero__tag-line"></span>
        EDUTAINMENT
      </span>

      <h1 class="edu-hero__title fade-up">
        Maverick Edutainment:<br>
        <em>Educational Tours That Bring Learning to Life</em>
      </h1>

      <h2 class="edu-hero__subtitle fade-up">
        Explore the World. Experience New Cultures. Learn Beyond the Classroom.
      </h2>

      <p class="edu-hero__description fade-up">
        Education does not have to remain inside a classroom. Maverick Edutainment creates educational tours and international study trips that combine learning, exploration, culture and entertainment in one meaningful experience.
      </p>

      <div class="edu-hero__highlights fade-up">
        <div class="edu-hero__highlight">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          <span>Educational institution visits</span>
        </div>
        <div class="edu-hero__highlight">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          <span>Cultural and historical exploration</span>
        </div>
        <div class="edu-hero__highlight">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          <span>Interactive workshops</span>
        </div>
        <div class="edu-hero__highlight">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          <span>Leadership and team-building</span>
        </div>
      </div>

      <p class="edu-hero__emphasis fade-up">
        <strong>Learning becomes more memorable when students experience it for themselves.</strong>
      </p>

      <div class="edu-hero__ctas fade-up">
        <a href="{{ route('contact') }}" class="btn btn--primary">Plan an Educational Tour</a>
        <a href="{{ route('contact') }}" class="btn btn--secondary">Request a Custom Itinerary</a>
        <a href="{{ route('contact') }}" class="btn btn--outline">Speak to Our Team</a>
      </div>
    </div>
  </div>
</section>
