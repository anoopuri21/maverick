<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('homepage_popup.enabled', false);
        $this->migrator->add('homepage_popup.image_url', null);
        $this->migrator->add('homepage_popup.image_url_asset_id', null);
        $this->migrator->add('homepage_popup.title', null);
        $this->migrator->add('homepage_popup.heading', null);
        $this->migrator->add('homepage_popup.subheading', null);
        $this->migrator->add('homepage_popup.body', null);
    }
};
