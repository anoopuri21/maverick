{{-- Gallery Section — Draggable Zig-Zag Carousel --}}
@if(($galleryImages ?? collect())->count() > 0)
<section id="gallery" class="os-gallery" aria-label="Gallery">
  <div class="container">
    <div class="os-gallery__header">
      <span class="os-section-label fade-up">{{ $storySections->gallery_badge ?? 'Moments' }}</span>
      <h2 class="os-section-heading os-section-heading--center fade-up">
        {!! $storySections->gallery_heading ?? 'Moments That <em>Define Us</em>' !!}
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
            @if($url = media_url($img->image_url ?? null))
            <img
              src="{{ $url }}"
              alt="{{ $img->caption ?? $img->category ?? 'Maverick Business Academy' }}"
              class="os-gallery__img"
              loading="{{ $r === 0 ? 'eager' : 'lazy' }}"
              draggable="false"
            />
            @if($img->caption || $img->category)
            <div class="os-gallery__caption">
              <span class="os-gallery__caption-text">{{ $img->caption ?: $img->category }}</span>
            </div>
            @endif
            @endif
          </div>
        </div>
        @endforeach
      @endfor
    </div>
  </div>
</section>
@endif
