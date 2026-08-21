<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpFinalCtaSettings extends Settings
{
    public ?string $eyebrow = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $sub = null;
    public ?string $description = null;
    public array $ctas = [];

    public static function group(): string
    {
        return 'gbp_final_cta';
    }
}
