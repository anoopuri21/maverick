<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpDocumentsSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public array $groups = [];

    public static function group(): string
    {
        return 'gbp_documents';
    }
}
