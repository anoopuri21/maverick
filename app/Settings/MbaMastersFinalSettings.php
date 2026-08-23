<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersFinalSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $plate_image = null;

    public ?string $plate_image_asset_id = null;

    public ?string $cta_primary_label = null;

    public ?string $cta_primary_url = null;

    public ?string $cta_secondary_label = null;

    public ?string $cta_secondary_url = null;

    public bool $show_form = true;

    public ?string $form_title = null;

    public static function group(): string
    {
        return 'mba_masters_final';
    }
}
