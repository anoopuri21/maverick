{{-- ===== S3: LEARNING BEYOND THE CLASSROOM ===== --}}
<section id="edu-learning-beyond" class="edu-learning-beyond section-wrapper section--light section--warm" aria-label="Learning Beyond the Classroom">
  <div class="container">
    <div class="edu-learning-beyond__header">
      <div class="section-label">
        <span>Beyond Classroom</span>
      </div>
      <h2 class="section-title">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">Learning Beyond</span>
        </span>
        <span class="text-reveal-wrapper hwdi__heading-line--red">
          <span class="text-reveal-inner">the <em>Classroom</em></span>
        </span>
      </h2>
      <p class="body-text fade-up">Some lessons are better understood when they are experienced.</p>
      <p class="body-text fade-up">A student can read about a country's history, but visiting its heritage sites creates a stronger connection to that history. A business learner can study innovation, but visiting a technology company allows them to see how innovation works in practice.</p>
      <p class="body-text fade-up"><strong>Maverick Edutainment helps connect academic ideas with real places, people and experiences.</strong></p>
    </div>

    <h3 class="edu-learning-beyond__subheading fade-up">Students can return from their journey with:</h3>

    <div class="edu-learning-beyond__grid">
      @php
        $beyondItems = [
          ['icon' => '🌍', 'title' => 'Greater cultural awareness'],
          ['icon' => '✈️', 'title' => 'Wider global exposure'],
          ['icon' => '💪', 'title' => 'Improved confidence and independence'],
          ['icon' => '🗣️', 'title' => 'Stronger communication skills'],
          ['icon' => '📚', 'title' => 'New academic and professional interests'],
          ['icon' => '🏭', 'title' => 'Better understanding of different industries'],
          ['icon' => '👥', 'title' => 'More meaningful relationships with classmates'],
          ['icon' => '⭐', 'title' => 'Memorable experiences connected to learning'],
        ];
      @endphp
      @foreach($beyondItems as $index => $item)
      <div class="edu-learning-beyond__card fade-up">
        <div class="edu-learning-beyond__card-top">
          <div class="edu-learning-beyond__card-icon">{{ $item['icon'] }}</div>
          <span class="edu-learning-beyond__card-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
        </div>
        <h4 class="edu-learning-beyond__card-title">{{ $item['title'] }}</h4>
      </div>
      @endforeach
    </div>
  </div>
</section>
