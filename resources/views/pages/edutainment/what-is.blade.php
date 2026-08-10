{{-- ===== S2: WHAT IS EDUTAINMENT ===== --}}
<section id="edu-what-is" class="edu-what-is section--light section-wrapper" aria-label="What Is Edutainment">
  <div class="container">
    <div class="edu-section-header edu-section-header--left">
      <div class="section-label"><span>Understanding Edutainment</span></div>
      <h2 class="section-title">What Is <em>Edutainment?</em></h2>
    </div>

    <div class="edu-what-is__frame">
      <div class="edu-what-is__wordmark fade-up">
        EDU<em>+</em><br>TAINMENT
        <span class="edu-what-is__wordmark-sub">Education &times; Entertainment</span>
      </div>

      <div class="edu-what-is__copy">
        <p class="edu-what-is__lead fade-up">
          Edutainment is the combination of <strong>education and entertainment</strong>.
        </p>
        <p class="fade-up">
          At Maverick, Edutainment means creating carefully planned educational journeys in which students learn while exploring new destinations, cultures, industries, institutions and communities.
        </p>
        <p class="fade-up">
          Instead of offering a conventional sightseeing trip, we create a structured learning journey where every visit has a purpose. The result is an experience that is informative, engaging, enjoyable and connected to the student's personal or academic development.
        </p>
      </div>
    </div>

    <div class="edu-what-is__list-wrapper fade-up">
      <h3 class="edu-what-is__list-title">A Maverick Edutainment programme may combine:</h3>
      <div class="edu-what-is__list">
        @php $items = [
          'Educational institution visits',
          'University and campus experiences',
          'Business and industry exposure',
          'Cultural and historical exploration',
          'Innovation and technology visits',
          'Interactive workshops',
          'Leadership and team-building activities',
          'Language and cultural immersion',
          'Recreational experiences',
          'Guided sightseeing',
          'Reflection and knowledge-sharing sessions',
        ]; @endphp
        @foreach($items as $item)
        <div class="edu-what-is__list-item fade-up">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          <span>{{ $item }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <div class="edu-what-is__quote fade-up">
      <p>&ldquo;Learning becomes more memorable when students experience it for themselves.&rdquo;</p>
    </div>
  </div>
</section>
