<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpComparisonSettings extends Settings
{
    public ?string $heading = null;
    public array $cards = [];
    public ?string $callout_label = null;
    public ?string $callout_value = null;
    public ?string $callout_description = null;

    public static function group(): string
    {
        return 'gbp_comparison';
    }
}
