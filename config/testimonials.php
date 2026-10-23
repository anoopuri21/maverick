<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Testimonial Display Pages Registry (Single Source of Truth)
    |--------------------------------------------------------------------------
    |
    | Register every public page that can display the shared Voices Testimonials
    | component (`<x-voices-testimonials page="page-key" />`).
    |
    | Adding a new page in the future requires ONLY two steps:
    |   1. Add `'new-page-key' => 'Page Label (/url)'` in `'pages'` below.
    |   2. Render `<x-voices-testimonials page="new-page-key" />` in that Blade view.
    |
    | The Admin multi-select "Display on Pages" dropdown, table page badges,
    | page filter, and frontend query/cache layer update automatically.
    |
    */
    'pages' => [
        'mba-masters-landing' => "Master's Landing Page (/online-mba-masters-uae)",
        'our-story' => 'Our Story Page (/our-story)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Section Copy
    |--------------------------------------------------------------------------
    |
    | Fallback header/caption copy used when neither component attributes nor
    | page-specific settings override them.
    |
    */
    'defaults' => [
        'label' => 'Testimonials',
        'heading' => 'What students say in their own words',
        'intro' => 'Real stories from working professionals advancing their global careers with Maverick Business Academy.',
        'stack_caption' => 'The experience, in their words.',
    ],
];
