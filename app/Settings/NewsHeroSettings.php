<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NewsHeroSettings extends Settings
{
    public ?string $eyebrow = null;
    public ?string $heading = null;
    public ?string $description = null;
    public ?string $image_url = null;

    public static function group(): string
    {
        return 'news_hero';
    }
}
