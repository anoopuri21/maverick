<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpAreasSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $sub = null;
    public array $cards = [];

    public static function group(): string
    {
        return 'gbp_areas';
    }
}
