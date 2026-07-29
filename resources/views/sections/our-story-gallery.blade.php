@if(isset($galleryImages) && $galleryImages->count() > 0)
<section id="story-gallery" class="story-gallery section-wrapper section--light" aria-label="Our Story Gallery">
    <div class="container">
        <div class="story-gallery__header">
            <div class="section-label"><span>Gallery</span></div>
            <h2 class="story-gallery__heading section-title">
                <span class="story-gallery__heading-line">
                    <span class="text-reveal-wrapper">
                        <span class="text-reveal-inner">Proof of Activity</span>
                    </span>
                </span>
            </h2>
        </div>

        <div class="story-gallery__grid">
            @foreach($galleryImages as $image)
                <div class="story-gallery__item">
                    <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? 'Gallery Image' }}" class="story-gallery__img" loading="lazy" />
                    @if($image->caption || $image->category)
                        <div class="story-gallery__overlay">
                            @if($image->category)
                                <span class="story-gallery__category">{{ $image->category }}</span>
                            @endif
                            @if($image->caption)
                                <p class="story-gallery__caption">{{ $image->caption }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
