<?php

return [
    /*
     * Public origin used for canonical URLs, sitemap entries and redirects.
     * Hosts are the aliases that should be consolidated onto that origin.
     */
    'preferred_origin' => [
        'url' => env('PREFERRED_ORIGIN_URL', 'https://mbalondon.org.uk'),
        'hosts' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('PREFERRED_ORIGIN_HOSTS', 'mbalondon.org.uk,www.mbalondon.org.uk'))
        ))),
    ],

    // Last-resort recipient for form notifications when all settings are empty.
    'fallback_email' => env('CONTACT_FALLBACK_EMAIL', 'fazil@mbalondon.org.uk'),
];
