<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CsrImpactSettings extends Settings
{
    public array $items = [];

    public static function group(): string
    {
        return 'csr_impact';
    }
}
