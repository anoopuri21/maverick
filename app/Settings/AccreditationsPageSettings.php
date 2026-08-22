<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AccreditationsPageSettings extends Settings
{
    public ?string $hero_tag = null;
    public ?string $hero_heading_line1 = null;
    public ?string $hero_heading_italic = null;
    public ?string $hero_description = null;
    public ?string $hero_background_image = null;
    public ?string $hero_background_image_asset_id = null;
    public ?string $credentials_label = null;
    public ?string $credentials_heading = null;
    public ?string $credentials_heading_span = null;
    public ?string $credentials_subtitle = null;
    public ?string $awards_label = null;
    public ?string $awards_heading = null;
    public ?string $awards_heading_span = null;
    public ?string $awards_subtitle = null;

    public static function group(): string
    {
        return 'accreditations_page';
    }
}