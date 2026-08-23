<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentInstitutionsSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_line2 = null;
    public ?string $title_italic = null;
    public bool $title_break = true;
    public ?string $intro = null;
    public array $tiles = [];
    public ?string $note = null;
    public array $ctas = [];

    public static function group(): string
    {
        return 'edutainment_institutions';
    }
}
