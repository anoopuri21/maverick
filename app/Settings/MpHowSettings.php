<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpHowSettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_highlight = null;
    public array $phases = [];
    public ?string $notice = null;

    public static function group(): string
    {
        return 'mp_how';
    }
}
