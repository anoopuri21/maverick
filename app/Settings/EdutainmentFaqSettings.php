<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentFaqSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_line2 = null;
    public ?string $title_italic = null;
    public bool $title_break = false;
    public array $items = [];

    public static function group(): string
    {
        return 'edutainment_faq';
    }
}
