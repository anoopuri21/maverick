<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CeoSettings extends Settings
{
    public ?string $label = null;

    public ?string $heading_line1 = null;

    public ?string $heading_line2 = null;

    public ?string $name = null;
    public ?string $designation = null;
    public ?string $quote = null;
    public ?string $body_paragraph1 = null;
    public ?string $body_paragraph2 = null;
    public ?string $image_url = null;
    public ?string $badge_text = null;

    public static function group(): string
    {
        return 'ceo';
    }
}