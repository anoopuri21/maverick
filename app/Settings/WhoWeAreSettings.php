<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhoWeAreSettings extends Settings
{
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;
    public ?string $body_text = null;
    public ?string $stat1_value = null;
    public ?string $stat1_suffix = null;
    public ?string $stat1_label = null;
    public ?string $stat2_value = null;
    public ?string $stat2_suffix = null;
    public ?string $stat2_label = null;
    public ?string $cta_text = null;
    public ?string $cta_url = null;
    public ?string $image_url = null;

    public static function group(): string
    {
        return 'who_we_are';
    }
}