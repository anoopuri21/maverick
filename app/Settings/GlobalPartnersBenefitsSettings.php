<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalPartnersBenefitsSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $main_image = null;
    public ?string $main_image_asset_id = null;
    public ?string $secondary_image = null;
    public ?string $secondary_image_asset_id = null;
    public ?string $stat_number = null;
    public ?string $stat_label = null;

    public array $items = [];

    public static function group(): string
    {
        return 'global_partners_benefits';
    }
}
