<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EdutainmentWhatIsSettings extends Settings
{
    public ?string $label = null;
    public ?string $title = null;
    public ?string $title_italic = null;
    public bool $title_break = false;
    public ?string $wordmark_line1 = null;
    public ?string $wordmark_plus = null;
    public ?string $wordmark_line2 = null;
    public ?string $wordmark_sub = null;
    public ?string $lead = null;
    public ?string $body = null;
    public ?string $list_title = null;
    public array $items = [];
    public ?string $quote = null;

    public static function group(): string
    {
        return 'edutainment_what_is';
    }
}
