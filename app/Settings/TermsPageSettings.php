<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TermsPageSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading_line1 = null;
    public ?string $heading_italic = null;
    public ?string $description = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;
    public ?string $center_image = null;
    public ?string $center_image_asset_id = null;
    public ?string $center_image_alt = null;
    public ?string $body = null;

    public static function group(): string
    {
        return 'terms_page';
    }
}
