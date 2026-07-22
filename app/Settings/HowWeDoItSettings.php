<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HowWeDoItSettings extends Settings
{
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;
    public ?string $subtitle = null;

    public ?string $step1_title = null;
    public ?string $step1_subtitle = null;
    public ?string $step1_desc = null;

    public ?string $step2_title = null;
    public ?string $step2_subtitle = null;
    public ?string $step2_desc = null;

    public ?string $step3_title = null;
    public ?string $step3_subtitle = null;
    public ?string $step3_desc = null;

    public static function group(): string
    {
        return 'how_we_do_it';
    }
}