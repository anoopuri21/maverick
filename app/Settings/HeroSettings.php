<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HeroSettings extends Settings
{
    public string $eyebrow;
    public string $headline_line1;
    public string $headline_line2;
    public string $headline_line3;
    public ?string $subheading = null;
    public string $cta_primary_text;
    public ?string $cta_primary_url = null;
    public string $cta_secondary_text;
    public ?string $cta_secondary_url = null;
    public ?string $video_url = null;

    public static function group(): string
    {
        return 'hero';
    }
}