<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpAudienceSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public ?string $statement = null;
    public array $items = [];

    public static function group(): string
    {
        return 'mp_audience';
    }
}
