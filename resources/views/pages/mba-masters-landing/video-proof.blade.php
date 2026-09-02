{{-- §14.5 Video Proof — inline YouTube film plate above testimonials --}}
@php
  $videoUrl = 'https://youtu.be/4p0rsCEljgo?si=7FHizEp4gkj6HPU7';
  $videoThumbnail = youtube_thumbnail_url($videoUrl);
  $videoEmbedUrl = youtube_embed_url($videoUrl, true);
@endphp

<section class="mlp-video-proof archive-video-proof" id="mlp-video-proof" aria-labelledby="mlp-video-proof-title">
  <div class="archive-video-proof__background" aria-hidden="true">
    <span class="archive-video-proof__wash mlp-wash"></span>
    <span class="archive-video-proof__line archive-video-proof__line--one"></span>
    <span class="archive-video-proof__line archive-video-proof__line--two"></span>
  </div>

  <div class="archive-video-proof__frame container">
    <header class="archive-video-proof__intro mlp-intro-grid">
      <div>
        <p class="archive-video-proof__label">Experience in motion</p>
        <h2 class="archive-video-proof__heading" id="mlp-video-proof-title">See the Maverick journey in motion.</h2>
      </div>
      <p class="archive-video-proof__intro-copy">A closer look at the people, ambition and learning culture behind the next chapter.</p>
    </header>

    <div
      class="archive-video-proof__player"
      data-inline-youtube
      data-video-embed="{{ $videoEmbedUrl }}"
      data-video-title="Maverick Business Academy experience"
    >
      @if($videoThumbnail && $videoEmbedUrl)
      <button type="button" class="archive-video-proof__poster" data-inline-youtube-trigger aria-label="Play the Maverick Business Academy video">
        <img
          src="{{ $videoThumbnail }}"
          alt="Maverick Business Academy video thumbnail"
          width="1920"
          height="1080"
          loading="lazy"
          decoding="async"
        >
        <span class="archive-video-proof__veil" aria-hidden="true"></span>
        <span class="archive-video-proof__play" aria-hidden="true">
          <i data-lucide="play"></i>
        </span>
        <span class="archive-video-proof__play-label" aria-hidden="true">Play film</span>
      </button>
      @else
      <p class="archive-video-proof__fallback">The video is currently unavailable.</p>
      @endif

      <noscript>
        @if($videoUrl)
        <a class="archive-video-proof__noscript-link" href="{{ $videoUrl }}">Watch the film on YouTube</a>
        @endif
      </noscript>
    </div>
  </div>
</section>
