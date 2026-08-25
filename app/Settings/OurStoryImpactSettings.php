<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStoryImpactSettings extends Settings
{
    public ?string $badge = null;
    public ?string $heading = null;
    public ?string $description = null;
    public ?string $stat_1_value = null;
    public ?string $stat_1_label = null;
    public ?string $stat_2_value = null;
    public ?string $stat_2_label = null;
    public ?string $stat_3_value = null;
    public ?string $stat_3_label = null;
    public ?string $stat_4_value = null;
    public ?string $stat_4_label = null;

    public static function group(): string
    {
        return 'our_story_impact';
    }
}
