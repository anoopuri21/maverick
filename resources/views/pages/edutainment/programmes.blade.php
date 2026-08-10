{{-- ===== S5: OUR EDUTAINMENT PROGRAMMES ===== --}}
<section id="edu-programmes" class="edu-programmes section-wrapper section--light" aria-label="Our Edutainment Programmes">
  <div class="container">
    <div class="edu-programmes__header">
      <div class="section-label"><span>Our Programmes</span></div>
      <h2 class="section-title">Our Edutainment<br><em>Programmes</em></h2>
    </div>

    <div class="edu-programmes__grid">
      {{-- UAE Educational Tours --}}
      <div class="edu-programmes__card fade-up">
        <div class="edu-programmes__card-image">
          <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80" alt="UAE Skyline" loading="lazy">
          <div class="edu-programmes__card-overlay"></div>
        </div>
        <div class="edu-programmes__card-content">
          <span class="edu-programmes__card-badge">UAE</span>
          <h3 class="edu-programmes__card-title">UAE Educational Tours for School Students</h3>
          <p class="edu-programmes__card-desc">Help students discover the UAE through a carefully planned combination of education, culture, innovation and entertainment.</p>
          <ul class="edu-programmes__card-list">
            <li>Emirati heritage and traditions</li>
            <li>UAE history and national development</li>
            <li>Science and technology</li>
            <li>Sustainability and environmental awareness</li>
            <li>Space exploration</li>
            <li>Architecture and engineering</li>
          </ul>
          <a href="{{ route('contact') }}" class="btn btn--secondary">Plan a UAE Student Tour</a>
        </div>
      </div>

      {{-- International Study Tours --}}
      <div class="edu-programmes__card fade-up">
        <div class="edu-programmes__card-image">
          <img src="https://images.pexels.com/photos/8828682/pexels-photo-8828682.jpeg" alt="International Students" loading="lazy">
          <div class="edu-programmes__card-overlay"></div>
        </div>
        <div class="edu-programmes__card-content">
          <span class="edu-programmes__card-badge">International</span>
          <h3 class="edu-programmes__card-title">International Study Tours</h3>
          <p class="edu-programmes__card-desc">Take learning beyond national borders through a structured international educational journey.</p>
          <ul class="edu-programmes__card-list">
            <li>University and campus visits</li>
            <li>Business and company exposure</li>
            <li>Innovation centres</li>
            <li>Cultural landmarks</li>
            <li>Local workshops</li>
            <li>Guided city exploration</li>
          </ul>
          <a href="{{ route('contact') }}" class="btn btn--secondary">Explore International Study Tours</a>
        </div>
      </div>

      {{-- China Study Tour --}}
      <div class="edu-programmes__card edu-programmes__card--featured fade-up">
        <div class="edu-programmes__card-image">
          <img src="https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=800&q=80" alt="China" loading="lazy">
          <div class="edu-programmes__card-overlay"></div>
        </div>
        <div class="edu-programmes__card-content">
          <span class="edu-programmes__card-badge">Featured</span>
          <h3 class="edu-programmes__card-title">China Educational and Business Study Tour</h3>
          <p class="edu-programmes__card-desc">Discover one of the world's most influential centres for business, technology, manufacturing, culture and innovation.</p>

          <div class="edu-programmes__card-topics">
            <div class="edu-programmes__card-topic">
              <h4>University Exposure</h4>
              <p>Visit selected universities and learn about their programmes, campuses, research environment and approach to education.</p>
            </div>
            <div class="edu-programmes__card-topic">
              <h4>Business and Industry Visits</h4>
              <p>Explore how organisations operate within sectors such as technology, manufacturing, e-commerce, finance, logistics and AI.</p>
            </div>
            <div class="edu-programmes__card-topic">
              <h4>Innovation and Entrepreneurship</h4>
              <p>Discover startup ecosystems, technology centres, innovation districts and emerging business models.</p>
            </div>
            <div class="edu-programmes__card-topic">
              <h4>Cultural Immersion</h4>
              <p>Experience Chinese history and traditions through cultural sites, local activities, art, food, language and community interaction.</p>
            </div>
            <div class="edu-programmes__card-topic">
              <h4>Student Interaction</h4>
              <p>Where available, connect with local or international students and exchange ideas about education, culture and career aspirations.</p>
            </div>
            <div class="edu-programmes__card-topic">
              <h4>Leadership and Global Business Learning</h4>
              <p>Participate in discussions, workshops or reflection sessions connected to international business, innovation, leadership and cross-cultural management.</p>
            </div>
          </div>

          <a href="{{ route('contact') }}" class="btn btn--primary">Request a China Study Tour Itinerary</a>
        </div>
      </div>
    </div>
  </div>
</section>
