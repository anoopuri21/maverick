<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class FacultyVoiceSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'faculty_voice_seo';
    }
}
