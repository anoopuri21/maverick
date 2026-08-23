<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $campusA = 'assets/images/edutainment/international-students-university-campus-1.jpg';
        $campusB = 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg';
        $campusC = 'assets/images/programs/enquire-seminar.jpg';
        $campusD = 'assets/images/edutainment/learning-beyond.png';

        $this->migrator->add('mba_masters_mba.index', '06');
        $this->migrator->add('mba_masters_mba.label', 'MBA specializations');
        $this->migrator->add('mba_masters_mba.heading', 'Find the MBA path that fits your career');
        $this->migrator->add(
            'mba_masters_mba.intro',
            'General, specialized, executive and global routes — browse by university partner and specialization.'
        );
        $this->migrator->add('mba_masters_mba.stage_image', $campusA);
        $this->migrator->add('mba_masters_mba.stage_image_asset_id', null);
        $this->migrator->add('mba_masters_mba.tabs', [
            [
                'key' => 'general',
                'label' => 'General MBA',
                'universities' => [
                    [
                        'name' => 'Rushford Business School, Switzerland',
                        'logo' => null,
                        'logo_asset_id' => null,
                        'image' => $campusA,
                        'image_asset_id' => null,
                        'programs' => [
                            ['title' => 'Master of Business Administration (MBA)'],
                            ['title' => 'MBA in Strategic Management'],
                            ['title' => 'MBA in Finance'],
                            ['title' => 'MBA in Marketing'],
                            ['title' => 'MBA in Human Resource Management'],
                        ],
                    ],
                    [
                        'name' => 'Girne American University, North Cyprus',
                        'logo' => null,
                        'logo_asset_id' => null,
                        'image' => $campusB,
                        'image_asset_id' => null,
                        'programs' => [
                            ['title' => 'MBA in Business Management'],
                            ['title' => 'MBA in Financial Management'],
                            ['title' => 'MBA in International Business Management'],
                            ['title' => 'MBA in Management Information Systems'],
                            ['title' => 'MBA in Marketing'],
                            ['title' => 'MBA in Data Science / Analytics Management'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'specialized',
                'label' => 'Specialized MBA',
                'universities' => [
                    [
                        'name' => 'Rushford Business School, Switzerland',
                        'logo' => null,
                        'logo_asset_id' => null,
                        'image' => $campusC,
                        'image_asset_id' => null,
                        'programs' => [
                            ['title' => 'MBA in Sustainability, Energy and Environment'],
                            ['title' => 'MBA in Real Estate Management'],
                            ['title' => 'MBA in Logistics & Supply Chain Management'],
                            ['title' => 'MBA in Healthcare Leadership'],
                            ['title' => 'MBA in Hospitality & Tourism Management'],
                            ['title' => 'MBA in Health Economics'],
                            ['title' => 'MBA in Entrepreneurship and Innovation'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'executive',
                'label' => 'Executive MBA',
                'universities' => [
                    [
                        'name' => 'University for the Creative Arts, UK + Rushford Business School, Switzerland',
                        'logo' => null,
                        'logo_asset_id' => null,
                        'image' => $campusD,
                        'image_asset_id' => null,
                        'programs' => [
                            ['title' => 'Educational Leadership'],
                            ['title' => 'Media & Entertainment'],
                            ['title' => 'Global Banking & Finance'],
                            ['title' => 'Health & Safety Leadership'],
                            ['title' => 'Renewable Energy & Sustainability'],
                            ['title' => 'Tourism & Hospitality Management'],
                            ['title' => 'Innovation & Entrepreneurship'],
                            ['title' => 'Project Management'],
                            ['title' => 'Human Resources Management'],
                            ['title' => 'Supply Chain Management'],
                            ['title' => 'Health Care Management'],
                            ['title' => 'Engineering Management'],
                            ['title' => 'Public Administration'],
                            ['title' => 'Public Health'],
                            ['title' => 'Digital Marketing'],
                            ['title' => 'Sport Management'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'global',
                'label' => 'Global MBA',
                'universities' => [
                    [
                        'name' => 'Global MBA + Rushford Business School, Switzerland',
                        'logo' => null,
                        'logo_asset_id' => null,
                        'image' => $campusB,
                        'image_asset_id' => null,
                        'programs' => [
                            ['title' => 'Global MBA'],
                        ],
                    ],
                ],
            ],
        ]);
    }
};
