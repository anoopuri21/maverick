<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CsrScholarshipSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $body = null;
    public array $items = [];

    public static function group(): string
    {
        return 'csr_scholarship';
    }
}
