<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentProgrammesSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_line2 = null;
    public ?string $title_italic = null;
    public bool $title_break = true;
    public array $cards = [];
    public array $china_items = [];
    public ?string $china_cta_label = null;
    public ?string $china_cta_url = null;

    public static function group(): string
    {
        return 'edutainment_programmes';
    }
}
