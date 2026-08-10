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
          <img src="{{ asset('assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg') }}" alt="UAE Skyline" loading="lazy">
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
          <img src="{{ asset('assets/images/edutainment/international-students-university-campus-1.jpg') }}" alt="International Students" loading="lazy">
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
          <img src="{{ asset('assets/images/edutainment/great-wall-china-travel-students-busines-2.jpg') }}" alt="China" loading="lazy">
          <div class="edu-programmes__card-overlay"></div>
        </div>
        <div class="edu-programmes__card-content">
          <span class="edu-programmes__card-badge">Featured</span>
          <h3 class="edu-programmes__card-title">China Educational and Business Study Tour</h3>
          <p class="edu-programmes__card-desc">Discover one of the world's most influential centres for business, technology, manufacturing, culture and innovation.</p>
        </div>
      </div>

      {{-- China Study Tour — Experience Cards (each in its own div with CTA) --}}
      <div class="edu-programmes__china">
        <div class="edu-programmes__china-item fade-up">
          <div class="edu-programmes__china-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          </div>
          <div class="edu-programmes__china-body">
            <h4 class="edu-programmes__china-title">University Exposure</h4>
            <p class="edu-programmes__china-desc">Visit selected universities and learn about their programmes, campuses, research environment and approach to education.</p>
          </div>
        </div>

        <div class="edu-programmes__china-item fade-up">
          <div class="edu-programmes__china-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
          </div>
          <div class="edu-programmes__china-body">
            <h4 class="edu-programmes__china-title">Business and Industry Visits</h4>
            <p class="edu-programmes__china-desc">Explore how organisations operate within sectors such as technology, manufacturing, e-commerce, finance, logistics and AI.</p>
          </div>
        </div>

        <div class="edu-programmes__china-item fade-up">
          <div class="edu-programmes__china-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></svg>
          </div>
          <div class="edu-programmes__china-body">
            <h4 class="edu-programmes__china-title">Innovation and Entrepreneurship</h4>
            <p class="edu-programmes__china-desc">Discover startup ecosystems, technology centres, innovation districts and emerging business models.</p>
          </div>
        </div>

        <div class="edu-programmes__china-item fade-up">
          <div class="edu-programmes__china-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 7v1a3 3 0 006 0V7m0 1a3 3 0 006 0V7m0 1a3 3 0 006 0V7"/><path d="M5 21V10.5M19 21V10.5"/></svg>
          </div>
          <div class="edu-programmes__china-body">
            <h4 class="edu-programmes__china-title">Cultural Immersion</h4>
            <p class="edu-programmes__china-desc">Experience Chinese history and traditions through cultural sites, local activities, art, food, language and community interaction.</p>
          </div>
        </div>

        <div class="edu-programmes__china-item fade-up">
          <div class="edu-programmes__china-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          </div>
          <div class="edu-programmes__china-body">
            <h4 class="edu-programmes__china-title">Student Interaction</h4>
            <p class="edu-programmes__china-desc">Where available, connect with local or international students and exchange ideas about education, culture and career aspirations.</p>
          </div>
        </div>

        <div class="edu-programmes__china-item fade-up">
          <div class="edu-programmes__china-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.2H22l-6 4.8 2.4 7.2L12 16.8 5.6 21.2 8 14 2 9.2h7.6z"/></svg>
          </div>
          <div class="edu-programmes__china-body">
            <h4 class="edu-programmes__china-title">Leadership and Global Business Learning</h4>
            <p class="edu-programmes__china-desc">Participate in discussions, workshops or reflection sessions connected to international business, innovation, leadership and cross-cultural management.</p>
          </div>
        </div>
      </div>

      <div class="edu-programmes__china-cta fade-up">
        <a href="{{ route('contact') }}" class="btn btn--primary">Request a China Study Tour Itinerary</a>
      </div>
    </div>
  </div>
</section>
