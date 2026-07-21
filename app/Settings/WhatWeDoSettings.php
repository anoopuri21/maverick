<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhatWeDoSettings extends Settings
{
    public string $heading_line1;
    public string $heading_line2;
    public ?string $context_text = null;

    public string $pillar1_title;
    public ?string $pillar1_desc = null;
    public string $pillar1_item1;
    public string $pillar1_item2;
    public string $pillar1_item3;
    public string $pillar1_cta_text;
    public ?string $pillar1_cta_url = null;

    public string $pillar2_title;
    public ?string $pillar2_desc = null;
    public string $pillar2_item1;
    public string $pillar2_item2;
    public string $pillar2_item3;
    public string $pillar2_cta_text;
    public ?string $pillar2_cta_url = null;

    public string $pillar3_title;
    public ?string $pillar3_desc = null;
    public string $pillar3_item1;
    public string $pillar3_item2;
    public string $pillar3_item3;
    public string $pillar3_item4;
    public string $pillar3_cta_text;
    public ?string $pillar3_cta_url = null;

    public static function group(): string
    {
        return 'what_we_do';
    }
}