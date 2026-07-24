<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStoryCeoQuoteSettings extends Settings
{
    public ?string $quote = null;
    public ?string $ceo_name = null;
    public ?string $ceo_designation = null;
    public ?string $ceo_image_url = null;

    public static function group(): string
    {
        return 'our_story_ceo_quote';
    }
}
