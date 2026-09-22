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

    public ?string $badge_kicker = null;

    public ?string $badge_title = null;

    public ?string $badge_line = null;

    public static function group(): string
    {
        return 'mba_masters_career';
    }
}
