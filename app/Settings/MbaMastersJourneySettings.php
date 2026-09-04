<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersJourneySettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public array $steps = [];

    public ?string $cta_label = null;

    public ?string $cta_url = null;

    public static function group(): string
    {
        return 'mba_masters_journey';
    }
}
