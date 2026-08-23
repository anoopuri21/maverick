<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpDestinationsSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public ?string $sub = null;
    public array $items = [];

    public static function group(): string
    {
        return 'mp_destinations';
    }
}
