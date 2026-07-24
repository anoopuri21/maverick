<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStoryTodaySettings extends Settings
{
    public ?string $badge = null;
    public ?string $heading = null;
    public ?string $description = null;
    public ?string $image_url = null;

    public static function group(): string
    {
        return 'our_story_today';
    }
}
