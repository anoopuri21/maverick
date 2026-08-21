<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaFaqSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public array $items = [];

    public static function group(): string
    {
        return 'dual_mba_faq';
    }
}
