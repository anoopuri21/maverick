<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaWhySettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_highlight = null;
    public array $cards = [];

    public static function group(): string
    {
        return 'dual_mba_why';
    }
}
