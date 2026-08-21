<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpProcessSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public array $steps = [];

    public static function group(): string
    {
        return 'mp_process';
    }
}
