<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersMbaSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $stage_image = null;

    public ?string $stage_image_asset_id = null;

    public array $tabs = [];

    public static function group(): string
    {
        return 'mba_masters_mba';
    }
}
