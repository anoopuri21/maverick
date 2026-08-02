{{-- Gallery Section for Our Story — Dynamic collage layout --}}
@if(($galleryImages ?? collect())->count() > 0)
<section id="gallery" class="os-gallery" aria-label="Gallery">
  <div class="container">
    <!-- <div class="os-gallery__header">
      <span class="os-section-label fade-up">Gallery</span>
      <h2 class="os-section-heading os-section-heading--center fade-up">Moments That <em>Define Us</em></h2>
    </div> -->

    <div class="os-gallery__grid" data-gallery-count="{{ $galleryImages->count() }}" data-gallery-grid>
      @foreach($galleryImages as $idx => $img)
      <div class="os-gallery__item fade-up" data-gallery-item="{{ $idx }}">
        <img
          src="{{ $img->image_url }}"
          alt="{{ $img->caption ?? 'Maverick Business Academy gallery image' }}"
          class="os-gallery__img"
          loading="lazy"
        />
        @if($img->caption)
        <div class="os-gallery__caption">
          <span class="os-gallery__caption-text">{{ $img->caption }}</span>
          @if($img->category)
          <span class="os-gallery__caption-cat">{{ $img->category }}</span>
          @endif
        </div>
        @endif
      </div>
      @endforeach
    </div>
  </div>

  {{-- Lightbox --}}
  <div class="os-lightbox" id="os-lightbox" aria-hidden="true">
    <button class="os-lightbox__close" data-lightbox-close aria-label="Close lightbox">
      <span data-lucide="x"></span>
    </button>
    <button class="os-lightbox__prev" data-lightbox-prev aria-label="Previous image">
      <span data-lucide="chevron-left"></span>
    </button>
    <button class="os-lightbox__next" data-lightbox-next aria-label="Next image">
      <span data-lucide="chevron-right"></span>
    </button>
    <div class="os-lightbox__content">
      <img class="os-lightbox__img" id="os-lightbox-img" src="" alt="" />
      <p class="os-lightbox__caption" id="os-lightbox-caption"></p>
    </div>
  </div>
</section>
@endif
