<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DualMbaFinalCtaSettings extends Settings
{
    public ?string $heading = null;
    public ?string $heading_line2 = null;
    public ?string $sub = null;
    public ?string $background_image = null;
    public ?string $background_image_asset_id = null;
    public array $ctas = [];
    public ?string $brochure_label = null;
    public ?string $brochure_url = null;

    public static function group(): string
    {
        return 'dual_mba_final_cta';
    }
}
