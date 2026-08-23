<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProgramsListingPageSettings extends Settings
{
    public ?string $hero_tag = null;
    public ?string $hero_heading = null;
    public ?string $hero_heading_italic = null;
    public ?string $hero_description = null;
    public ?string $hero_background_image = null;
    public ?string $hero_background_image_asset_id = null;
    public ?string $cta_label = null;
    public ?string $empty_message = null;
    public ?string $card_cta_label = null;

    public static function group(): string
    {
        return 'programs_listing_page';
    }
}