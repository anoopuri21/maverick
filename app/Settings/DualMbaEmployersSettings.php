<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaEmployersSettings extends Settings
{
    public array $collage = [];
    public ?string $counter_label = null;
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $description = null;
    public array $items = [];

    public static function group(): string
    {
        return 'dual_mba_employers';
    }
}
