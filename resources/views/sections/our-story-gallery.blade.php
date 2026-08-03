{{-- Gallery Section — Draggable Zig-Zag Carousel --}}
@if(($galleryImages ?? collect())->count() > 0)
<section id="gallery" class="os-gallery" aria-label="Gallery">
  <div class="container">
    <div class="os-gallery__header">
      <span class="os-section-label fade-up">Moments</span>
      <h2 class="os-section-heading os-section-heading--center fade-up">
        Moments That <em>Define Us</em>
      </h2>
    </div>
  </div>

  {{-- Draggable Zig-Zag Carousel --}}
  <div class="os-gallery__carousel" data-gallery-carousel>
    <div class="os-gallery__track" data-gallery-track>
      {{-- Duplicate images for infinite loop --}}
      @for($r = 0; $r < 3; $r++)
        @foreach($galleryImages as $idx => $img)
        <div class="os-gallery__card {{ $idx % 3 === 0 ? 'os-gallery__card--tall' : ($idx % 3 === 1 ? 'os-gallery__card--medium' : 'os-gallery__card--short') }}" data-gallery-card>
          <div class="os-gallery__card-inner">
            <img
              src="{{ $img->image_url }}"
              alt="{{ $img->caption ?? 'Maverick Business Academy' }}"
              class="os-gallery__img"
              loading="{{ $r === 0 ? 'eager' : 'lazy' }}"
              draggable="false"
            />
            @if($img->caption)
            <div class="os-gallery__caption">
              <span class="os-gallery__caption-text">{{ $img->caption }}</span>
            </div>
            @endif
          </div>
        </div>
        @endforeach
      @endfor
    </div>
  </div>
</section>
@endif
