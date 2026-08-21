<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class ContactSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'contact_seo';
    }
}
