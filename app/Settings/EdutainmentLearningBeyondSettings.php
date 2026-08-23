<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentLearningBeyondSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_line2 = null;
    public ?string $title_italic = null;
    public bool $title_break = true;
    public ?string $body = null;
    public ?string $image = null;
    public ?string $image_asset_id = null;
    public ?string $cards_heading = null;
    public array $cards = [];

    public static function group(): string
    {
        return 'edutainment_learning_beyond';
    }
}
