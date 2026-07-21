<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalOpportunitiesSettings extends Settings
{
    public string $heading;
    public ?string $subtitle = null;

    public string $left_title;

    public string $opp1_title;
    public ?string $opp1_desc = null;
    public ?string $opp1_url = null;

    public string $opp2_title;
    public ?string $opp2_desc = null;
    public ?string $opp2_url = null;

    public string $opp3_title;
    public ?string $opp3_desc = null;
    public ?string $opp3_url = null;

    public string $opp4_title;
    public ?string $opp4_desc = null;
    public ?string $opp4_url = null;

    public string $right_title;

    public string $path1_title;
    public ?string $path1_desc = null;
    public ?string $path1_url = null;

    public string $path2_title;
    public ?string $path2_desc = null;
    public ?string $path2_url = null;

    public string $path3_title;
    public ?string $path3_desc = null;
    public ?string $path3_url = null;

    public string $path4_title;
    public ?string $path4_desc = null;
    public ?string $path4_url = null;

    public static function group(): string
    {
        return 'global_opportunities';
    }
}