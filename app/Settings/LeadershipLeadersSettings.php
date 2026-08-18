<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LeadershipLeadersSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $subheading = null;
    public array $items = [];

    public static function group(): string
    {
        return 'leadership_leaders';
    }
}
