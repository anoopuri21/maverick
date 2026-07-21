<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhoWeAreSettings extends Settings
{
    public string $heading_line1;
    public string $heading_line2;
    public ?string $body_text = null;
    public string $stat1_value;
    public string $stat1_suffix;
    public string $stat1_label;
    public string $stat2_value;
    public string $stat2_suffix;
    public string $stat2_label;
    public string $cta_text;
    public ?string $cta_url = null;
    public ?string $image_url = null;

    public static function group(): string
    {
        return 'who_we_are';
    }
}