<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_class.index', '09');
        $this->migrator->add('mba_masters_class.label', 'Class profile');
        $this->migrator->add('mba_masters_class.heading', 'Built for working professionals');
        $this->migrator->add(
            'mba_masters_class.intro',
            'Our online MBA & Master\'s cohorts bring together full-time employees, managers and executives who study while they lead.'
        );
        $this->migrator->add(
            'mba_masters_class.audience',
            'Designed for professionals balancing career, family and ambition — not a full-time campus-only route.'
        );
        $this->migrator->add('mba_masters_class.metrics', [
            [
                'value' => 'Working pros',
                'label' => 'Primary learner profile',
            ],
            [
                'value' => 'Managers+',
                'label' => 'Mid to senior leadership',
            ],
            [
                'value' => 'Online+',
                'label' => 'Live sessions · flexible pace',
            ],
        ]);
        $this->migrator->add('mba_masters_class.regions', [
            ['name' => 'UAE', 'note' => 'Home base'],
            ['name' => 'India', 'note' => 'Strong cohort'],
            ['name' => 'GCC', 'note' => 'Regional peers'],
            ['name' => 'Africa', 'note' => 'Growing network'],
            ['name' => 'Southeast Asia', 'note' => 'Cross-border'],
        ]);
        $this->migrator->add('mba_masters_class.industries', [
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
