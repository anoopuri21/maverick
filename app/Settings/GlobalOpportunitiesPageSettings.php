<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalOpportunitiesPageSettings extends Settings
{
    // ─── Hero ───
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $description = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;

    // ─── Overview ───
    public ?string $overview_label = null;
    public ?string $overview_heading = null;
    public ?string $overview_heading_italic = null;
    public ?string $overview_body = null;

    // ─── Cards section header ───
    public ?string $cards_label = null;
    public ?string $cards_heading = null;
    public ?string $cards_heading_italic = null;

    public static function group(): string
    {
        return 'global_opportunities_page';
    }
}
