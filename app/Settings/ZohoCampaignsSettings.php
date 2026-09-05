<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ZohoCampaignsSettings extends Settings
{
    public bool $enabled = false;

    public ?string $region = 'com';

    /** 'campaigns' = classic campaigns.zoho.com API, 'marketing_automation' = new-stack marketingautomation.zoho.com API (new Zoho accounts / new Campaigns UI). */
    public ?string $api_stack = 'campaigns';

    public ?string $client_id = null;

    public ?string $client_secret = null;

    public ?string $refresh_token = null;

    public ?string $list_key = null;

    public ?string $source = 'Website Footer';

    public static function group(): string
    {
        return 'zoho_campaigns';
    }
}
