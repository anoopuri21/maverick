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

    public ?string $film_label = null;

    public ?string $film_heading = null;

    public ?string $film_body = null;

    public ?string $film_play_label = null;

    public static function group(): string
    {
        return 'mba_masters_testimonials';
    }
}
