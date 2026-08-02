{{-- ===== S12: FINAL CTA ===== --}}
<section id="edu-cta" class="edu-cta" aria-label="Transform a Student Trip">
  <div class="edu-cta__bg" aria-hidden="true">
    <div class="edu-cta__bg-image" style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80')"></div>
    <div class="edu-cta__overlay"></div>
  </div>

  <div class="container">
    <div class="edu-cta__content">
      <h2 class="edu-cta__heading fade-up">
        Transform a Student Trip into a<br><em>Learning Journey</em>
      </h2>
      <p class="edu-cta__description fade-up">
        Let your students discover new cultures, industries, institutions and ideas through an experience they will remember.
      </p>
      <p class="edu-cta__description fade-up">
        Whether you are planning a school educational tour within the UAE or an international student study trip to China, Maverick Edutainment can help turn your objective into a structured learning experience.
      </p>
      <p class="edu-cta__emphasis fade-up">
        <strong>See more. Experience more. Learn more.</strong>
      </p>

      <div class="edu-cta__buttons fade-up">
        <a href="{{ route('contact') }}" class="btn btn--primary">Plan Your Educational Tour</a>
        <a href="{{ route('contact') }}" class="btn btn--secondary">Request an Itinerary</a>
        <a href="https://wa.me/{{ $site->whatsapp_number ?? '' }}" class="btn btn--outline" target="_blank" rel="noopener noreferrer">Enquire on WhatsApp</a>
      </div>
    </div>
  </div>
</section>
