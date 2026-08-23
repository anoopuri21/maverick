<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaProcessSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public array $steps = [];

    public static function group(): string
    {
        return 'dual_mba_process';
    }
}
