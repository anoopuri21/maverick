<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentFinalCtaSettings extends Settings
{
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $body = null;
    public ?string $emphasis = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;
    public array $ctas = [];
    public ?string $whatsapp_label = null;
    public bool $show_whatsapp = true;

    public static function group(): string
    {
        return 'edutainment_final_cta';
    }
}
