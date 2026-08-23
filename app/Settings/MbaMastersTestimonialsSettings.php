<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersTestimonialsSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public array $items = [];

    public static function group(): string
    {
        return 'mba_masters_testimonials';
    }
}
