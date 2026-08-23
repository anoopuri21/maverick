<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_why.index', '04');
        $this->migrator->add('mba_masters_why.label', 'Why choose Maverick');
        $this->migrator->add('mba_masters_why.heading', 'Built for professionals who lead while they learn');
        $this->migrator->add(
            'mba_masters_why.intro',
            'Six reasons working executives across the UAE choose Maverick for MBA & Master\'s pathways.'
        );
        $this->migrator->add('mba_masters_why.chapters', [
            [
                'title' => 'Flexible learning',
                'text' => 'Designed for working professionals and busy executives — study around your career, not instead of it.',
                'anchor' => null,
            ],
            [
                'title' => 'Academic support',
                'text' => 'Counselling, academic guidance, student support and structured onboarding from enquiry to graduation.',
                'anchor' => null,
            ],
            [
                'title' => 'Wide specialization choice',
                'text' => 'Explore MBA and Master\'s specializations across management, finance, marketing, logistics, healthcare and more.',
                'anchor' => '#mlp-mba',
            ],
            [
                'title' => 'Affordable payment options',
                'text' => 'Cost-effective pathways with flexible payment plans — clarity on fees without a hard-sell discount tone.',
                'anchor' => '#mlp-enquire',
            ],
            [
                'title' => 'International pathways',
                'text' => 'Partner universities and global exposure that support recognised MBA & Master\'s search intent.',
                'anchor' => '#mlp-partners',
            ],
            [
                'title' => 'Community and events',
                'text' => 'Alumni networks, graduations, webinars, masterclasses and corporate training that keep learning alive.',
                'anchor' => null,
            ],
        ]);
    }
};
