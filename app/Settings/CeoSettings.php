<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CeoSettings extends Settings
{
    public string $name;
    public string $designation;
    public string $quote;
    public ?string $body_paragraph1 = null;
    public ?string $body_paragraph2 = null;
    public ?string $image_url = null;
    public string $badge_text;

    public static function group(): string
    {
        return 'ceo';
    }
}