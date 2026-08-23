<section id="global-access-points" class="gap section-wrapper section--light" aria-label="Our Global Maverick Access Points">
  <div class="container gap__inner">
    <div class="gap__header">
      <div class="section-label">
        <span>Global Reach</span>
      </div>
      <h2 class="gap__heading section-title">
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Our Global</span>
          </span>
        </span>
        <span class="hwdi__heading-line hwdi__heading-line--red">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Maverick Access Points</span>
          </span>
        </span>
      </h2>
    </div>

    <div class="gap-map" data-lenis-prevent>
      <div id="gap-loader" class="gap-map__loader" aria-hidden="true">
        <div class="gap-map__loader-dots">
          <span class="gap-map__loadsq"></span>
          <span class="gap-map__loadsq"></span>
          <span class="gap-map__loadsq"></span>
        </div>
      </div>

      <div class="gap-map__dots"></div>
      <div class="gap-map__vignette"></div>

      <div class="gap-map__bracket gap-map__bracket--tl"></div>
      <div class="gap-map__bracket gap-map__bracket--tr"></div>
      <div class="gap-map__bracket gap-map__bracket--bl"></div>
      <div class="gap-map__bracket gap-map__bracket--br"></div>
      <div class="gap-map__corner-dot"></div>

      <svg class="gap-map__target" viewBox="0 0 30 30" aria-hidden="true">
        <g stroke="rgba(5,11,29,.75)" stroke-width="1" fill="none">
          <circle cx="15" cy="15" r="10"/>
          <path d="M15 0v8M15 22v8M0 15h8M22 15h8"/>
        </g>
        <circle cx="15" cy="15" r="1.6" fill="#b20202" stroke="none"/>
      </svg>

      <div class="gap-map__compass" aria-hidden="true">
        <i data-lucide="compass"></i>
      </div>

      <div class="gap-map__canvas">
        <svg id="gap-map" viewBox="0 0 1280 680" role="img" aria-label="World map of Maverick access points"></svg>
      </div>

      <div class="gap-map__controls">
        <button class="gap-map__ctl" id="gap-zin" type="button" aria-label="Zoom in"><i data-lucide="plus"></i></button>
        <button class="gap-map__ctl" id="gap-zout" type="button" aria-label="Zoom out"><i data-lucide="minus"></i></button>
        <button class="gap-map__ctl" id="gap-zreset" type="button" aria-label="Reset view"><i data-lucide="rotate-ccw"></i></button>
      </div>
    </div>
  </div>
</section>
