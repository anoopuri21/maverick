<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalPartnersOverviewSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $paragraph = null;
    public ?string $image = null;
    public ?string $image_alt = null;
    public ?string $image_asset_id = null;

    public static function group(): string
    {
        return 'global_partners_overview';
    }
}
