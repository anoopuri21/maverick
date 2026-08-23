<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersCareerSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public array $stories = [];

    public static function group(): string
    {
        return 'mba_masters_career';
    }
}
