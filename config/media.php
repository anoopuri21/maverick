<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media library (Cloudinary-backed)
    |--------------------------------------------------------------------------
    |
    | Permanent media is stored on Cloudinary only. The server keeps temporary
    | Livewire uploads briefly; never mirror full media libraries to disk.
    |
    */

    'max_upload_kilobytes' => (int) env('MEDIA_MAX_UPLOAD_KB', 5120), // 5 MB

    // Milliseconds to wait between Cloudinary API calls during account
    // migration (shared-hosting safe default, same as media:sync/clean).
    'migration_throttle_ms' => (int) env('MEDIA_MIGRATION_THROTTLE_MS', 250),

    'allowed_mime_prefixes' => [
        'image/',
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
        'media_assets',
        'media_recycle_logs',
        // Migration bookkeeping (old/new urls + asset ids): never live
        // references — scanners must not count or rewrite these rows.
        'media_migration_map',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
        'pulse_values',
        'pulse_entries',
        'pulse_aggregates',
    ],

];
