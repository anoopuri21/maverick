<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FinalCtaSettings extends Settings
{
    public string $heading;
    public ?string $subtitle = null;
    public string $btn_primary_text;
    public ?string $btn_primary_url = null;
    public string $btn_secondary_text;
    public ?string $btn_secondary_url = null;
    public string $phone_text;
    public string $phone_number;

    public static function group(): string
    {
        return 'final_cta';
    }
}