<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpIntroSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;
    public ?string $heading_italic = null;
    public array $paragraphs = [];
    public array $highlights = [];

    public static function group(): string
    {
        return 'gbp_intro';
    }
}
