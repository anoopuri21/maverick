<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class GlobalOpportunitiesSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'global_opportunities_seo';
    }
}
