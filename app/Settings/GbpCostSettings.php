<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpCostSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $description = null;
    public ?string $closing = null;
    public array $comparisons = [];

    public static function group(): string
    {
        return 'gbp_cost';
    }
}
