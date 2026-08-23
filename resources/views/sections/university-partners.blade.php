<section id="university-partners" class="partners section-wrapper section--dark" aria-label="University Partners">
  <div class="container partners__inner">
    <!-- ========== HEADER ========== -->
    <div class="partners__header">
      <div class="section-label"><span>Global Network</span></div>
      <h2 class="partners__heading section-title">
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Our Global</span>
          </span>
        </span>
        <span class="hwdi__heading-line">
          <span class="text-reveal-wrapper">
            <span class="text-reveal-inner">Academic Network</span>
          </span>
        </span>
      </h2>
      <!-- <p class="partners__subtitle body-text">
          Click any country to explore our partner universities and the
          programs they offer through Maverick.
        </p> -->
    </div>

    <!-- ========== MOBILE COUNTRY LIST ========== -->
    <div class="partners__mobile-list" id="partnersMobileList">
      <!-- Country buttons dynamically rendered by JS -->
    </div>
    <div class="partners__desktop" id="partnersDesktop">
      <!-- ========== MAP STAGE (D3-Powered, Desktop Only) ========== -->
      <div class="partners__map-stage" id="partnersMapStage">
        <div class="partners__map-container" id="partnersMapContainer">
          <!-- D3 renders SVG map here -->
        </div>
      </div>


      <!-- ========== DETAIL PANEL ========== -->
      <div class="partners__detail-panel" id="partnersDetailPanel">
        <div class="partners__detail-header">
          <h3 class="partners__detail-country" id="partnersDetailCountry">
            United Arab Emirates
          </h3>
          <span class="partners__detail-count" id="partnersDetailCount">
            1 University
          </span>
        </div>

        <div class="partners__universities" id="partnersUniversities">
          <!-- University cards dynamically rendered by JS -->
        </div>
      </div>
    </div>
  </div>

 <script>
    window.universityPartnersData = @json($universityPartnersJson ?? []);
</script>
{{-- #region agent log --}}
@php
    file_put_contents(base_path('debug-216c24.log'), json_encode(['sessionId' => '216c24', 'hypothesisId' => 'B', 'location' => 'sections/university-partners.blade.php', 'message' => 'section rendered', 'data' => ['route' => request()->route()?->getName(), 'path' => request()->path(), 'jsonCount' => is_countable($universityPartnersJson ?? null) ? count($universityPartnersJson) : null, 'partnersCount' => is_countable($universityPartners ?? null) ? count($universityPartners) : null, 'partnersJsRouteGate' => request()->routeIs('home', 'our-story', 'masters-pathway', 'global-partners')], 'timestamp' => (int) round(microtime(true) * 1000), 'runId' => 'post-fix']).PHP_EOL, FILE_APPEND);
@endphp
{{-- #endregion --}}
</section>
