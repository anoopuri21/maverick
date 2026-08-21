<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaOverviewSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public ?string $description = null;
    public ?string $highlights_heading = null;
    public ?string $highlights_line = null;
    public array $cards = [];

    public static function group(): string
    {
        return 'dual_mba_overview';
    }
}
