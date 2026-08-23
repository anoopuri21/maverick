<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaTwiceSettings extends Settings
{
    public array $slides = [];

    public static function group(): string
    {
        return 'dual_mba_twice';
    }
}
