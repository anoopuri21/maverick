<?php

use Illuminate\Support\Str;

if (! function_exists('media_url')) {
    /**
     * Normalize a media URL for output.
     *
     * Site settings (e.g. logos) may store either a full absolute URL
     * (Cloudinary) or a relative path like "assets/images/logo.png".
     * Relative paths must be resolved with asset() so they work on sub-routes
     * (e.g. /programs/{slug}) and never produce /programs/assets/... links.
     *
     * @param  string|null  $path
     * @param  string|null  $fallback  local path used when $path is empty
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        $value = $path ?: $fallback;

        if (empty($value)) {
            return null;
        }

        // Already an absolute URL (http/https/data/blob) or protocol-relative.
        if (Str::startsWith($value, ['http://', 'https://', '//', 'data:', 'blob:'])) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }
}
