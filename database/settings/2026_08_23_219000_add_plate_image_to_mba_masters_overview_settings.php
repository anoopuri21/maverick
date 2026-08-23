<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('mba_masters_overview.plate_image')) {
            $this->migrator->add('mba_masters_overview.plate_image', 'assets/images/homepage/mba-management.jpg');
        }

        if (! $this->migrator->exists('mba_masters_overview.plate_image_asset_id')) {
            $this->migrator->add('mba_masters_overview.plate_image_asset_id', null);
        }
    }
};
