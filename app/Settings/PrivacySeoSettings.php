<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class PrivacySeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'privacy_seo';
    }
}
