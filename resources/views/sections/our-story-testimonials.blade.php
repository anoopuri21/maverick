{{--
    Backwards-compatible delegate wrapper:
    Any legacy `@include('sections.our-story-testimonials')` automatically renders
    the shared `<x-voices-testimonials>` component scoped to `our-story`.
--}}
<x-voices-testimonials
    :page="$page ?? \App\Support\TestimonialPages::OUR_STORY"
    :id="$id ?? 'mlp-testimonials'"
    :label="$osTestimonialsLabel ?? $badge ?? null"
    :heading="$osTestimonialsHeading ?? $heading ?? null"
    :intro="$intro ?? null"
    :show="$show ?? true"
/>
