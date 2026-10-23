@php
    use App\Support\TestimonialPages;
@endphp

<x-voices-testimonials
    :page="TestimonialPages::MBA_MASTERS_LANDING"
    id="mlp-testimonials"
    :label="$testimonials->label"
    :heading="$testimonials->heading"
    :intro="$testimonials->intro"
    :show="(bool) ($testimonials->show_section ?? true)"
/>
