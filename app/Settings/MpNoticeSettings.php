<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MpNoticeSettings extends Settings
{
    public ?string $label = null;
    public ?string $body = null;

    public static function group(): string
    {
        return 'mp_notice';
    }
}
