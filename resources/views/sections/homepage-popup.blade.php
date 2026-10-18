@php
  $homepagePopup = $homepagePopup ?? safe_settings(\App\Settings\HomepagePopupSettings::class);
  $popupImage = media_url($homepagePopup->image_url ?? null);
  $popupTitle = trim((string) ($homepagePopup->title ?? ''));
  $popupHeading = trim((string) ($homepagePopup->heading ?? ''));
  $popupSubheading = trim((string) ($homepagePopup->subheading ?? ''));
  $popupBody = rich_html($homepagePopup->body ?? null);
  $popupHasCopy = $popupTitle !== '' || $popupHeading !== '' || $popupSubheading !== '' || $popupBody !== '';
  $popupLabelId = $popupHeading !== '' ? 'homepage-popup-heading' : ($popupTitle !== '' ? 'homepage-popup-title' : null);
@endphp

@if(($homepagePopup->enabled ?? false) && filled($popupImage))
<div class="home-popup" id="homepage-popup" role="dialog" aria-modal="true" @if($popupLabelId) aria-labelledby="{{ $popupLabelId }}" @else aria-label="Announcement" @endif>
  <div class="home-popup__backdrop" data-home-popup-close></div>
  <div class="home-popup__dialog">
    <div class="home-popup__bar">
      <button type="button" class="home-popup__close" data-home-popup-close aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="home-popup__media">
      <img class="home-popup__image" src="{{ $popupImage }}" alt="" width="1200" height="1500" decoding="async">
      @if($popupHasCopy)
      <div class="home-popup__copy" data-lenis-prevent>
        @if($popupTitle !== '')
        <p class="home-popup__title" @if($popupLabelId === 'homepage-popup-title') id="homepage-popup-title" @endif>{{ $popupTitle }}</p>
        @endif
        @if($popupHeading !== '')
        <h2 class="home-popup__heading" @if($popupLabelId === 'homepage-popup-heading') id="homepage-popup-heading" @endif>{{ $popupHeading }}</h2>
        @endif
        @if($popupSubheading !== '')
        <p class="home-popup__subheading">{{ $popupSubheading }}</p>
        @endif
        @if($popupBody !== '')
        <div class="home-popup__body">{!! $popupBody !!}</div>
        @endif
      </div>
      @endif
    </div>
  </div>
</div>
<script>
  (function () {
    var popup = document.getElementById('homepage-popup');
    if (!popup) return;

    function stopScroll() {
      document.body.classList.add('home-popup-open');
      if (window.lenisInstance && typeof window.lenisInstance.stop === 'function') {
        window.lenisInstance.stop();
      }
    }

    function closePopup() {
      if (popup.hidden) return;
      popup.hidden = true;
      document.body.classList.remove('home-popup-open');
      if (window.lenisInstance && typeof window.lenisInstance.start === 'function') {
        window.lenisInstance.start();
      }
    }

    stopScroll();
    document.addEventListener('lenisReady', function () {
      if (!popup.hidden) stopScroll();
    }, { once: true });

    popup.addEventListener('click', function (event) {
      if (event.target.closest('[data-home-popup-close]')) closePopup();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') closePopup();
    });
  })();
</script>
@endif
