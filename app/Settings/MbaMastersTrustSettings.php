<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersTrustSettings extends Settings
{
    public ?string $label = null;

    public ?string $quote = null;

    public ?string $quote_attribution = null;

    public array $stats = [];

    public static function group(): string
    {
        return 'mba_masters_trust';
    }
}
