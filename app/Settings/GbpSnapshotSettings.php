<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpSnapshotSettings extends Settings
{
    public array $cards = [];
    public array $ctas = [];

    public static function group(): string
    {
        return 'gbp_snapshot';
    }
}
