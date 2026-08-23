<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersLearningSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $plate_image = null;

    public ?string $plate_image_asset_id = null;

    public ?string $plate_caption = null;

    public array $points = [];

    public ?string $cta_primary_label = null;

    public ?string $cta_primary_url = null;

    public ?string $cta_secondary_label = null;

    public ?string $cta_secondary_url = null;

    public static function group(): string
    {
        return 'mba_masters_learning';
    }
}
