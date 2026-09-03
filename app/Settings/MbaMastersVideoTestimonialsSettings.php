<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersVideoTestimonialsSettings extends Settings
{
    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public array $videos = [];

    public static function group(): string
    {
        return 'mba_masters_video_testimonials';
    }
}
