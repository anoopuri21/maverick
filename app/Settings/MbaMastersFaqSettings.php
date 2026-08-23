<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersFaqSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public array $items = [];

    public static function group(): string
    {
        return 'mba_masters_faq';
    }
}
