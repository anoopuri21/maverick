<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpWhySettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $quote = null;
    public ?string $paragraph = null;
    public array $items = [];

    public static function group(): string
    {
        return 'gbp_why';
    }
}
