<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CsrCommitmentSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $body = null;
    public ?string $image_url = null;
    public ?string $image_url_asset_id = null;

    public static function group(): string
    {
        return 'csr_commitment';
    }
}
