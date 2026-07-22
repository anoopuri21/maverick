<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhatWeDoSettings extends Settings
{
    public ?string $heading_line1 = null;
    public ?string $heading_line2 = null;
    public ?string $context_text = null;

    public ?string $pillar1_title = null;
    public ?string $pillar1_desc = null;
    public ?string $pillar1_item1 = null;
    public ?string $pillar1_item2 = null;
    public ?string $pillar1_item3 = null;
    public ?string $pillar1_cta_text = null;
    public ?string $pillar1_cta_url = null;

    public ?string $pillar2_title = null;
    public ?string $pillar2_desc = null;
    public ?string $pillar2_item1 = null;
    public ?string $pillar2_item2 = null;
    public ?string $pillar2_item3 = null;
    public ?string $pillar2_cta_text = null;
    public ?string $pillar2_cta_url = null;

    public ?string $pillar3_title = null;
    public ?string $pillar3_desc = null;
    public ?string $pillar3_item1 = null;
    public ?string $pillar3_item2 = null;
    public ?string $pillar3_item3 = null;
    public ?string $pillar3_item4 = null;
    public ?string $pillar3_cta_text = null;
    public ?string $pillar3_cta_url = null;

    public static function group(): string
    {
        return 'what_we_do';
    }
}