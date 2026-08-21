<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpOverviewSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public array $paragraphs = [];
    public array $phases = [];

    public static function group(): string
    {
        return 'mp_overview';
    }
}
