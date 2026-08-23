<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_overview.index', '03');
        $this->migrator->add('mba_masters_overview.label', 'Program overview');
        $this->migrator->add(
            'mba_masters_overview.heading',
            'MBA & Master\'s Programs Designed for Working Professionals'
        );
        $this->migrator->add(
            'mba_masters_overview.intro',
            'A career-focused pathway built for UAE professionals who need flexibility without compromising academic rigor.'
        );
        $this->migrator->add('mba_masters_overview.items', [
            [
                'title' => 'Online & flexible study',
                'text' => 'Learn around your work schedule — study from anywhere in the UAE or beyond.',
            ],
            [
                'title' => 'Assignment-based learning',
                'text' => 'Apply concepts through practical assignments designed for working professionals.',
            ],
            [
                'title' => 'Dedicated academic support',
                'text' => 'Guidance from admissions through assessment so you never study alone.',
            ],
            [
                'title' => 'Weekend & flexible classes',
                'text' => 'Session formats that respect full-time careers and family commitments.',
            ],
            [
                'title' => 'Career-focused curriculum',
                'text' => 'Specializations and modules aligned to progression in marketing, finance, IT, logistics, and more.',
            ],
        ]);
        $this->migrator->add('mba_masters_overview.cta_primary_label', 'Check Eligibility');
        $this->migrator->add('mba_masters_overview.cta_primary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_overview.cta_secondary_label', 'Request Fee Plan');
        $this->migrator->add('mba_masters_overview.cta_secondary_url', '#mlp-enquire');
    }
};
