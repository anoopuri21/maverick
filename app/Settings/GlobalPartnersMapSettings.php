<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalPartnersMapSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;

    public static function group(): string
    {
        return 'global_partners_map';
    }
}
