<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('zoho_campaigns.enabled', false);
        $this->migrator->add('zoho_campaigns.region', 'com');
        $this->migrator->add('zoho_campaigns.client_id', null);
        $this->migrator->add('zoho_campaigns.client_secret', null, true);
        $this->migrator->add('zoho_campaigns.refresh_token', null, true);
        $this->migrator->add('zoho_campaigns.list_key', null);
        $this->migrator->add('zoho_campaigns.source', 'Website Footer');
    }
};
