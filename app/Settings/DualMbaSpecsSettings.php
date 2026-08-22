<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaSpecsSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_highlight = null;
    public bool $title_break = true;
    public ?string $intro = null;
    public array $cards = [];

    public static function group(): string
    {
        return 'dual_mba_specs';
    }
}
