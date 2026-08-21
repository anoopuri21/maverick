<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaHeroSettings extends Settings
{
    public ?string $tag = null;
    public ?string $headline_line1 = null;
    public ?string $headline_line2 = null;
    public ?string $headline_italic = null;
    public ?string $sub = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;
    public ?string $visual_image = null;
    public ?string $visual_image_asset_id = null;
    public ?string $badge_title = null;
    public ?string $badge_sub = null;
    public array $stats = [];
    public array $ctas = [];

    public static function group(): string
    {
        return 'dual_mba_hero';
    }
}
