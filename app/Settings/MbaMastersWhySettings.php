<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersWhySettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public array $chapters = [];

    public static function group(): string
    {
        return 'mba_masters_why';
    }
}
