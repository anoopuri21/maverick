<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class StudentSuccessSeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'student_success_seo';
    }
}
