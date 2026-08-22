<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaTestimonialsSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_italic = null;
    public array $items = [];

    public static function group(): string
    {
        return 'dual_mba_testimonials';
    }
}
