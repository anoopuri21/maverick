<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('mba_masters_class.industries', fn () => [
            [
                'name' => 'Marketing',
                'share' => '22',
                'image' => 'assets/images/homepage/business.jpg',
                'image_asset_id' => null,
            ],
            [
                'name' => 'Logistics',
                'share' => '18',
                'image' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
                'image_asset_id' => null,
            ],
            [
                'name' => 'Cyber Security',
                'share' => '16',
                'image' => 'assets/images/homepage/dba.jpg',
                'image_asset_id' => null,
            ],
            [
                'name' => 'Finance',
                'share' => '24',
                'image' => 'assets/images/homepage/swiss-mba.jpg',
                'image_asset_id' => null,
            ],
            [
                'name' => 'IT',
                'share' => '20',
                'image' => 'assets/images/homepage/mba-management.jpg',
                'image_asset_id' => null,
            ],
        ]);
    }
};
