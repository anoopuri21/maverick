<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersMastersSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $stage_image = null;

    public ?string $stage_image_asset_id = null;

    public array $universities = [];

    public static function group(): string
    {
        return 'mba_masters_masters';
    }
}
