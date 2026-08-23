<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $campusA = 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg';
        $campusB = 'assets/images/edutainment/international-students-university-campus-1.jpg';
        $campusC = 'assets/images/programs/enquire-seminar.jpg';
        $campusD = 'assets/images/edutainment/learning-beyond.png';

        $this->migrator->add('mba_masters_masters.index', '07');
        $this->migrator->add('mba_masters_masters.label', 'Master\'s programs');
        $this->migrator->add('mba_masters_masters.heading', 'Master\'s, MSc & LLM pathways');
        $this->migrator->add(
            'mba_masters_masters.intro',
            'Explore Master\'s options by university partner — built for professionals searching for Master\'s in Dubai / UAE online routes.'
        );
        $this->migrator->add('mba_masters_masters.stage_image', $campusA);
        $this->migrator->add('mba_masters_masters.stage_image_asset_id', null);
        $this->migrator->add('mba_masters_masters.universities', [
            [
                'name' => 'Rushford Business School, Switzerland — MSc',
                'logo' => null,
                'logo_asset_id' => null,
                'image' => $campusB,
                'image_asset_id' => null,
                'programs' => [
                    ['title' => 'MSc in Sustainability and Environmental Management'],
                    ['title' => 'MSc in Strategic Management'],
                    ['title' => 'MSc in Operations and Supply Chain Management'],
                    ['title' => 'MSc in International Business Management'],
                    ['title' => 'MSc in Marketing'],
                    ['title' => 'MSc in Entrepreneurship & Innovation'],
                    ['title' => 'MSc in Finance and Investment'],
                    ['title' => 'MSc in Economics'],
                    ['title' => 'MSc in Business Management'],
                ],
            ],
            [
                'name' => 'Girne American University, North Cyprus — MSc with Thesis',
                'logo' => null,
                'logo_asset_id' => null,
                'image' => $campusC,
                'image_asset_id' => null,
                'programs' => [
                    ['title' => 'MSc in Business Management'],
                    ['title' => 'MSc in Economics'],
                    ['title' => 'MSc in Healthcare Management'],
                    ['title' => 'MSc in Counselling Psychology'],
                ],
            ],
            [
                'name' => 'University of Wolverhampton, UK',
                'logo' => null,
                'logo_asset_id' => null,
                'image' => $campusD,
                'image_asset_id' => null,
                'programs' => [
                    ['title' => 'Master of Laws (LLM)'],
                ],
            ],
            [
                'name' => 'University for the Creative Arts, UK + Rushford Business School, Switzerland',
                'logo' => null,
                'logo_asset_id' => null,
                'image' => $campusA,
                'image_asset_id' => null,
                'programs' => [
                    ['title' => 'Global MBA route (listed under Master\'s / MBA options)'],
                ],
            ],
        ]);
    }
};
