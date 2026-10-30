<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Registered Testimonial Display Pages
    |--------------------------------------------------------------------------
    |
    | Baseline page registry merged with dynamic pages managed in the Admin Panel
    | (`Global Content -> Testimonials -> Section & Pages Settings`, stored in DB
    | table `settings` under group `voices_testimonials`).
    |
    */
    'pages' => [
        'mba-masters-landing' => "Master's Landing Page (/online-mba-masters-uae)",
        'our-story' => 'Our Story Page (/our-story)',
        'program-detail' => 'Program Detail Pages (/programs/{slug})',
    ],
];
