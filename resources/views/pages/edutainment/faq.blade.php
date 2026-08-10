{{-- ===== S11: FREQUENTLY ASKED QUESTIONS ===== --}}
<section id="edu-faq" class="edu-faq section-wrapper section--light" aria-label="Frequently Asked Questions">
  <div class="container">
    <div class="edu-faq__header">
      <div class="section-label">
        <span>FAQs</span>
      </div>
      <h2 class="section-title">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">Frequently Asked <em>Questions</em></span>
        </span>
      </h2>
    </div>

    <div class="edu-faq__list">
      @php
        $faqs = [
          ['q' => 'What does Edutainment mean?', 'a' => 'Edutainment combines education with entertainment. Maverick Edutainment uses travel, cultural experiences, academic visits, industry exposure and enjoyable activities to make learning more interactive.'],
          ['q' => 'Is Edutainment the same as a normal tour?', 'a' => 'No. A normal tour is primarily focused on sightseeing or recreation. An Edutainment programme is planned around specific learning objectives while still giving students opportunities to explore and enjoy the destination.'],
          ['q' => 'Who can participate?', 'a' => 'Programmes can be designed for school students, college students, university learners, MBA students, doctoral students, professionals and educational institutions.'],
          ['q' => 'Do you organise both UAE and international tours?', 'a' => 'Yes. Programmes may be planned within the UAE or for international destinations such as China, subject to availability and final confirmation.'],
          ['q' => 'Can schools request a customised itinerary?', 'a' => 'Yes. The itinerary can be adapted according to the students\' age, learning theme, destination, group size, programme duration and budget.'],
          ['q' => 'What is included in the programme?', 'a' => 'Inclusions depend on the selected package. They may include accommodation, transportation, meals, guided visits, educational activities, entry tickets, workshops, cultural experiences and programme coordination.'],
          ['q' => 'Are flights included?', 'a' => 'Flights may be included or excluded depending on the package requested. This will be clearly mentioned in the final proposal.'],
          ['q' => 'Do students receive a certificate?', 'a' => 'Participation certificates may be included for selected programmes. Certificate availability will be confirmed before the tour.'],
          ['q' => 'How long are the tours?', 'a' => 'Programmes may range from a one-day UAE educational visit to a multi-day or international study tour.'],
          ['q' => 'Can the tour be connected to a school subject?', 'a' => 'Yes. Programmes can be developed around subjects such as business, science, sustainability, history, culture, technology, engineering, tourism or leadership.'],
          ['q' => 'Can parents or teachers accompany the students?', 'a' => 'Accompanying arrangements can be discussed based on the institution\'s requirements, student age, destination and group structure.'],
          ['q' => 'How early should we contact Maverick?', 'a' => 'Institutions should contact Maverick as early as possible, particularly for international programmes that may require institutional approvals, documentation and travel arrangements.'],
          ['q' => 'Is visa assistance available?', 'a' => 'Visa-related guidance or coordination may be available depending on the destination and selected package. Visa approval remains subject to the relevant authority.'],
          ['q' => 'How is the programme price calculated?', 'a' => 'The cost depends on the destination, number of travellers, programme duration, accommodation, transport, activities, meals, flights and other requested services.'],
          ['q' => 'How can we request a proposal?', 'a' => 'Submit your institution name, group size, student age, preferred destination, travel period and learning objectives. Our team can then prepare an initial programme proposal.'],
        ];
      @endphp

      @foreach($faqs as $index => $faq)
      <div class="edu-faq__item {{ $index === 0 ? 'is-open' : '' }}" data-testid="edu-faq-item-{{ $index + 1 }}">
        <button class="edu-faq__question" data-testid="edu-faq-q-{{ $index + 1 }}">
          <span>{{ $faq['q'] }}</span>
          <span class="edu-faq__icon">+</span>
        </button>
        <div class="edu-faq__answer" style="{{ $index === 0 ? 'max-height: none;' : '' }}">
          <div class="edu-faq__answer-inner">
            <p>{{ $faq['a'] }}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
