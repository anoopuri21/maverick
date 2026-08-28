<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalPartnersJourneySettings extends Settings
{
    public ?string $label = null;
    public ?string $heading = null;
    public ?string $heading_italic = null;
    public ?string $subheading = null;
    public ?string $filter_all_label = null;

    public static function group(): string
    {
        return 'global_partners_journey';
    }
}
