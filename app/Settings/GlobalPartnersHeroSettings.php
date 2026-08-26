<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalPartnersHeroSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading_line1 = null;
    public ?string $heading_italic = null;
    public ?string $description = null;
    public ?string $scroll_hint = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;

    public static function group(): string
    {
        return 'global_partners_hero';
    }
}
