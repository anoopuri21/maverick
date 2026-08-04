<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AccreditationCinematicSettings extends Settings
{
    public ?string $heading = null;
    public ?string $text = null;
    public ?string $image_url = null;
    public ?string $image_url_asset_id = null;

    public static function group(): string
    {
        return 'accreditation_cinematic';
    }
}
