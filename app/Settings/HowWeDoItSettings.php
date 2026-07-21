<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HowWeDoItSettings extends Settings
{
    public string $heading_line1;
    public string $heading_line2;
    public ?string $subtitle = null;

    public string $step1_title;
    public ?string $step1_subtitle = null;
    public ?string $step1_desc = null;

    public string $step2_title;
    public ?string $step2_subtitle = null;
    public ?string $step2_desc = null;

    public string $step3_title;
    public ?string $step3_subtitle = null;
    public ?string $step3_desc = null;

    public static function group(): string
    {
        return 'how_we_do_it';
    }
}