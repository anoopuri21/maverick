<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NumbersSettings extends Settings
{
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;
    public ?string $heading_line3 = null;
    public ?string $context_text = null;
    public ?string $context_link_text = null;
    public ?string $context_link_url = null;

    public ?string $stat1_value = null;
    public ?string $stat1_label = null;
    public ?string $stat2_value = null;
    public ?string $stat2_label = null;
    public ?string $stat3_value = null;
    public ?string $stat3_label = null;
    public ?string $stat4_value = null;
    public ?string $stat4_label = null;
    public ?string $stat5_value = null;
    public ?string $stat5_label = null;
    public ?string $stat6_value = null;
    public ?string $stat6_label = null;

    public static function group(): string
    {
        return 'numbers';
    }
}