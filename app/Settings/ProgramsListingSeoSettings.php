<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class ProgramsListingSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'programs_listing_seo';
    }
}
