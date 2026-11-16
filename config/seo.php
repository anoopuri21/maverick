<?php

return [
    // Enable only after verifying HTTPS/proxy behavior in the production environment.
    // Prefer a single redirect at the CDN/web server, which also covers static files.
    'origin_redirect_enabled' => env('SEO_ORIGIN_REDIRECT_ENABLED', false),
];
