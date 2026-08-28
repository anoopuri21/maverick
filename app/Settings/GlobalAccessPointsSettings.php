<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalAccessPointsSettings extends Settings
{
    public ?string $label = null;

    public ?string $heading_line1 = null;

    public ?string $heading_line2 = null;

    public ?string $story_label = null;

    public ?string $story_heading = null;

    public ?string $story_body = null;

    public ?string $hint = null;

    public ?string $canvas_aria = null;

    public static function group(): string
    {
        return 'global_access_points';
    }
}
