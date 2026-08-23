<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersCompareSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $col_online = null;

    public ?string $col_traditional = null;

    public array $rows = [];

    public ?string $cta_label = null;

    public ?string $cta_url = null;

    public static function group(): string
    {
        return 'mba_masters_compare';
    }
}
