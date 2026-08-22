<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GbpAdmissionSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $eligibility_title = null;
    public array $eligibility = [];
    public ?string $entry_title = null;
    public array $entry_requirements = [];
    public ?string $note = null;

    public static function group(): string
    {
        return 'gbp_admission';
    }
}
