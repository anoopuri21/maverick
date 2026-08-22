<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpOverviewSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public array $paragraphs = [];
    public ?string $quote = null;
    public array $stages = [];
    public ?string $panel_label = null;
    public ?string $panel_title = null;
    public array $panel_stats = [];

    public static function group(): string
    {
        return 'gbp_overview';
    }
}
