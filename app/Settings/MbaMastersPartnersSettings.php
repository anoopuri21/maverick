<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersPartnersSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $trust_line = null;

    public static function group(): string
    {
        return 'mba_masters_partners';
    }
}
