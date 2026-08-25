<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStoryVisionSettings extends Settings
{
    public ?string $badge = null;
    public ?string $heading = null;
    public ?string $description = null;
    public ?string $background_image_url = null;
    public ?string $cta_label = null;
    public ?string $cta_url = null;

    public static function group(): string
    {
        return 'our_story_vision';
    }
}
