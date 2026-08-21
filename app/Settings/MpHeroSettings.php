<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpHeroSettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public ?string $sub = null;
    public array $paragraphs = [];
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;
    public array $ctas = [];
    public array $route_steps = [];

    public static function group(): string
    {
        return 'mp_hero';
    }
}
