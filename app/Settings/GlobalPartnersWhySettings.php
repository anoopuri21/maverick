<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalPartnersWhySettings extends Settings
{
    public ?string $tag = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $quote = null;

    public array $items = [];

    public static function group(): string
    {
        return 'global_partners_why';
    }
}
