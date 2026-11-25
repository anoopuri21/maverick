<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Self-hosted media library
    |--------------------------------------------------------------------------
    |
    | Permanent media lives on the application's public disk, not on a third
    | party CDN. /storage is a symlink to storage/app/public (create it with
    | `php artisan storage:link`). Keep this directory outside Git and include
    | it in the server's off-site backup routine.
    |
    */

    'storage_disk' => env('MEDIA_STORAGE_DISK', 'public'),

    'storage_prefix' => env('MEDIA_STORAGE_PREFIX', 'media'),

    'remote_download_timeout_seconds' => (int) env('MEDIA_REMOTE_DOWNLOAD_TIMEOUT', 90),

    'max_upload_kilobytes' => (int) env('MEDIA_MAX_UPLOAD_KB', 5120), // 5 MB

    'allowed_mime_prefixes' => [
        'image/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy bootstrap image recovery
    |--------------------------------------------------------------------------
    |
    | The two CSS backgrounds and old default-settings images now point to
    | deterministic local paths. Keep their legacy source URLs here so a
    | recovery on a fresh server can copy their files before Cloudinary closes.
    |
    */

    'legacy_static_cloudinary_urls' => [
        // CSS-only backgrounds
        'https://res.cloudinary.com/i08gwudw/image/upload/v1783605569/bgm-wm_mav1.png',
        'https://res.cloudinary.com/i08gwudw/image/upload/v1785600206/dotted-grid-paper-bg.jpg',
        // Default settings images now point to their local deterministic paths.
        // Keep their former source URLs here so a fresh recovery can copy them.
        'https://res.cloudinary.com/i08gwudw/image/upload/v1783478429/ASD01131_v3opum.jpg',
        'https://res.cloudinary.com/i08gwudw/image/upload/v1785340343/maverick-academy/our-story/timeline/qpg8khpl9f0tg6xzyhz5.jpg',
        'https://res.cloudinary.com/i08gwudw/image/upload/v1784534077/maverick-academy/programs/igxpmziapl3v5xaqozki.jpg',
        'https://res.cloudinary.com/i08gwudw/image/upload/v1784441348/mba_sa4pmo.jpg',
        'https://res.cloudinary.com/i08gwudw/image/upload/v1785846422/csr-impact_bh8qyb.png',
    ],

    'schema_skip_tables' => [
        'migrations',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'sessions',
        'password_reset_tokens',
        'personal_access_tokens',
        'media_recycle_logs',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
        'pulse_values',
        'pulse_entries',
        'pulse_aggregates',
    ],

];
