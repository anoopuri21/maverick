<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhyMaverickSettings extends Settings
{
    // Required
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;
    public ?string $subtitle = null;

    // Tiles — descriptions nullable (only titles required)
    public ?string $tile1_title = null;
    public ?string $tile1_desc = null;
    public ?string $tile2_title = null;
    public ?string $tile2_desc = null;
    public ?string $tile3_title = null;
    public ?string $tile3_desc = null;
    public ?string $tile4_title = null;
    public ?string $tile4_desc = null;
    public ?string $tile5_title = null;
    public ?string $tile5_desc = null;
    public ?string $tile6_title = null;
    public ?string $tile6_desc = null;

    public static function group(): string
    {
        return 'why_maverick';
    }
}