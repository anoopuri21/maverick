<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NumbersSettings extends Settings
{
    public string $heading_line1;
    public string $heading_line2;
    public string $heading_line3;
    public ?string $context_text = null;
    public string $context_link_text;
    public ?string $context_link_url = null;

    public string $stat1_value;
    public string $stat1_label;
    public string $stat2_value;
    public string $stat2_label;
    public string $stat3_value;
    public string $stat3_label;
    public string $stat4_value;
    public string $stat4_label;
    public string $stat5_value;
    public string $stat5_label;
    public string $stat6_value;
    public string $stat6_label;

    public static function group(): string
    {
        return 'numbers';
    }
}