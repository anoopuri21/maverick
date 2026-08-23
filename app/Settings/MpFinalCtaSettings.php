<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpFinalCtaSettings extends Settings
{
    public ?string $eyebrow = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public ?string $sub = null;
    public ?string $description = null;
    public array $ctas = [];
    public array $contacts = [];

    public static function group(): string
    {
        return 'mp_final_cta';
    }
}
