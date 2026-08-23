<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class PathwayProgramsSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'pathway_programs_seo';
    }
}
