<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersHeroSettings extends Settings
{
    public ?string $eyebrow = null;

    public ?string $headline = null;

    public ?string $subheading = null;

    public ?string $background_image = null;

    public ?string $background_image_asset_id = null;

    public ?string $cta_primary_label = null;

    public ?string $cta_primary_url = null;

    public ?string $cta_secondary_label = null;

    public ?string $cta_secondary_url = null;

    public ?string $cta_tertiary_label = null;

    public ?string $cta_tertiary_url = null;

    public ?string $form_title = null;

    public static function group(): string
    {
        return 'mba_masters_hero';
    }
}
