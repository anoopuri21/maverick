<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'mba_masters_class.label',
            fn () => 'Executive MBA · 2025 cohort · Online format'
        );
        $this->migrator->update(
            'mba_masters_class.heading',
            fn () => 'A cohort built for working professionals'
        );
        $this->migrator->update(
            'mba_masters_class.intro',
            fn () => 'Meet the full-time employees, managers and executives who bring different industries, markets and points of view into the learning room.'
        );
        $this->migrator->update(
            'mba_masters_class.audience',
            fn () => 'Designed for professionals balancing career, family and ambition.'
        );
        $this->migrator->update('mba_masters_class.metrics', fn () => [
            [
                'value' => '281',
                'label' => 'Participants',
            ],
            [
                'value' => '13 years 2 months',
                'label' => 'Average in-class work experience',
            ],
            [
                'value' => '33',
                'label' => 'Average age',
            ],
            [
                'value' => '20%',
                'label' => 'Women participation',
            ],
        ]);
        $this->migrator->update('mba_masters_class.regions', fn () => [
            ['name' => 'India', 'note' => null],
            ['name' => 'Turkey', 'note' => null],
            ['name' => 'UAE', 'note' => null],
            ['name' => 'Singapore', 'note' => null],
            ['name' => 'China', 'note' => null],
            ['name' => 'Jordan', 'note' => null],
            ['name' => 'Philippines', 'note' => null],
            ['name' => 'USA', 'note' => null],
            ['name' => 'Malaysia', 'note' => null],
            ['name' => 'Indonesia', 'note' => null],
            ['name' => 'Bahrain', 'note' => null],
            ['name' => 'Qatar', 'note' => null],
            ['name' => 'Netherlands', 'note' => null],
        ]);
        $this->migrator->update('mba_masters_class.industries', fn () => [
            [
                'name' => 'IT & related fields',
                'share' => '35',
                'image' => null,
                'image_asset_id' => null,
            ],
            [
                'name' => 'Consumer & Retail',
                'share' => '26',
                'image' => null,
                'image_asset_id' => null,
            ],
            [
                'name' => 'Engineering & Manufacturing',
                'share' => '18',
                'image' => null,
                'image_asset_id' => null,
            ],
            [
                'name' => 'Financial Services',
                'share' => '12',
                'image' => null,
                'image_asset_id' => null,
            ],
            [
                'name' => 'Others / Misc.',
                'share' => '8',
                'image' => null,
                'image_asset_id' => null,
            ],
        ]);
    }
};
