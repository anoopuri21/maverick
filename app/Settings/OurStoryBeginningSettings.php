<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStoryBeginningSettings extends Settings
{
    public ?string $badge = null;
    public ?string $heading = null;
    public ?string $paragraph_1 = null;
    public ?string $paragraph_2 = null;
    public ?string $image_url = null;

    public static function group(): string
    {
        return 'our_story_beginning';
    }
}
