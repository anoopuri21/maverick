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

    public ?string $class_year_lead = null;

    public ?string $class_year_strong = null;

    public ?string $class_year_body = null;

    public ?string $class_year_center = null;

    public array $class_year_stats = [];

    public ?string $global_heading = null;

    public ?string $global_line = null;

    public static function group(): string
    {
        return 'mba_masters_class';
    }
}
