<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HeroSettings extends Settings
{
    public ?string $eyebrow = null;
    public ?string $headline_line1 = null;
    public ?string $headline_line2 = null;
    public ?string $headline_line3 = null;
    public ?string $subheading = null;
    public ?string $cta_primary_text = null;
    public ?string $cta_primary_url = null;
    public ?string $cta_secondary_text = null;
    public ?string $cta_secondary_url = null;
    public ?string $video_url = null;

    public static function group(): string
    {
        return 'hero';
    }
}