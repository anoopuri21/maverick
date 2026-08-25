<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStoryHeroSettings extends Settings
{
    public ?string $eyebrow = null;
    public ?string $heading = null;
    public ?string $subtitle = null;
    public ?string $description = null;
    public ?string $cta_label = null;
    public ?string $cta_url = null;
    public ?string $scroll_hint = null;
    public ?string $image_url = null;

    public static function group(): string
    {
        return 'our_story_hero';
    }
}
