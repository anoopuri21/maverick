<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FinalCtaSettings extends Settings
{
    public ?string $heading = null;
    public ?string $subtitle = null;
    public ?string $btn_primary_text = null;
    public ?string $btn_primary_url = null;
    public ?string $btn_secondary_text = null;
    public ?string $btn_secondary_url = null;
    public ?string $phone_text = null;
    public ?string $phone_number = null;

    public static function group(): string
    {
        return 'final_cta';
    }
}