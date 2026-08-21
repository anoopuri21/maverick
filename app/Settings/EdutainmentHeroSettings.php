<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentHeroSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $description = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;

    public static function group(): string
    {
        return 'edutainment_hero';
    }
}
