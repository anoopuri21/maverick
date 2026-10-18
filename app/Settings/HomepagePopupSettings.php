<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepagePopupSettings extends Settings
{
    public bool $enabled = false;

    public ?string $image_url = null;

    public ?string $image_url_asset_id = null;

    public ?string $title = null;

    public ?string $heading = null;

    public ?string $subheading = null;

    public ?string $body = null;

    public static function group(): string
    {
        return 'homepage_popup';
    }
}
