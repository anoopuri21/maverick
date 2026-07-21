<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $logo_url = null;
    public ?string $logo_white_url = null;
    public string $phone;
    public string $whatsapp_number;
    public string $email;
    public string $address;
    public ?string $facebook_url = null;
    public ?string $instagram_url = null;
    public ?string $linkedin_url = null;
    public ?string $youtube_url = null;
    public ?string $apply_now_url = null;

    public static function group(): string
    {
        return 'site';
    }
}