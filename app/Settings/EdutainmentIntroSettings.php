<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentIntroSettings extends Settings
{
    public ?string $label = null;
    public ?string $title_line1 = null;
    public ?string $title_line2 = null;
    public ?string $title_line2_italic = null;
    public ?string $title_line3 = null;
    public ?string $title_line3_italic = null;
    public ?string $body = null;
    public ?string $emphasis = null;
    public array $ctas = [];

    public static function group(): string
    {
        return 'edutainment_intro';
    }
}
