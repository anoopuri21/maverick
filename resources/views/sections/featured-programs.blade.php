<section id="featured-programs" class="programs section-wrapper section--light" aria-label="Featured Programs">
  <div class="container programs__inner">
    <div class="section-label">
      <span>Programs</span>
    </div>
    <h2 class="programs__heading section-title">
      <span class="hwdi__heading-line">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">Most In-Demand</span>
        </span>
      </span>
      <span class="hwdi__heading-line hwdi__heading-line--red">
        <span class="text-reveal-wrapper">
          <span class="text-reveal-inner">Programs</span>
        </span>
      </span>
    </h2>
    <p class="programs__subtitle body-text fade-up">
      Industry-aligned qualifications designed to accelerate global
      careers
    </p>
  </div>

  <div class="programs__scroll-wrapper">
    <div class="programs__track">
      @forelse(($featuredPrograms ?? collect()) as $index => $program)
        <div class="programs__card">
          @if($url = media_url($program->image_url ?? null, 'assets/images/homepage/mba.jpg'))
          <img class="programs__card-media"
               src="{{ $url }}"
               alt="{{ $program->title }}"
               loading="lazy" decoding="async" width="800" height="540" />
          @endif
          <div class="programs__card-header">
            <span class="programs__card-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
            @if($program->universityPartner)
              <span class="programs__card-badge">{{ $program->universityPartner->name }}</span>
            @endif
          </div>
          <div class="programs__card-body">
            <h3 class="programs__card-title">{{ $program->title }}</h3>
            @if($program->short_description)
              <p class="programs__card-subtitle">{{ $program->short_description }}</p>
            @endif
            <div class="programs__card-line"></div>
            @if(filled($program->slug))
            <a href="{{ route('programs.show', $program->slug) }}" class="programs__card-link">
              Learn More
              <span class="programs__card-arrow" aria-hidden="true">
                <span class="inline-icon" data-lucide="move-right"></span>
              </span>
            </a>
            @endif
          </div>
        </div>
      @empty
        <p class="body-text" style="padding: 2rem;">No featured programs yet.</p>
      @endforelse
    </div>
  </div>
</section>
