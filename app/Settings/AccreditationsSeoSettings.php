<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class AccreditationsSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'accreditations_seo';
    }
}
