<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersClassSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $audience = null;

    public array $metrics = [];

    public array $regions = [];

    public array $industries = [];

    public static function group(): string
    {
        return 'mba_masters_class';
    }
}
