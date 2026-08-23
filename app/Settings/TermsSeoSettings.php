<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class TermsSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'terms_seo';
    }
}
