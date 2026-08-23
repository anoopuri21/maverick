<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_career.index', '10');
        $this->migrator->add('mba_masters_career.label', 'Career progression');
        $this->migrator->add('mba_masters_career.heading', 'Career Growth After MBA & Master\'s');
        $this->migrator->add(
            'mba_masters_career.intro',
            'Promotion, leadership moves and career switches — real trajectories from working professionals in our cohorts. Portraits are placeholders until client-approved photos.'
        );
        $this->migrator->add('mba_masters_career.stories', [
            [
                'name' => 'Aisha Rahman',
                'country' => 'UAE',
                'program' => 'Online MBA — Finance',
                'previous_role' => 'Senior Analyst, regional bank',
                'current_role' => 'Finance Manager, GCC conglomerate',
                'quote' => 'The program fitted around my role — I stepped up without pausing my career.',
                'portrait' => 'assets/images/alumni/alumn-1.png',
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Rohan Mehta',
                'country' => 'India',
                'program' => 'Specialized MBA — Logistics',
                'previous_role' => 'Operations Lead, 3PL',
                'current_role' => 'Head of Supply Chain, e-commerce',
                'quote' => 'Assignment-based learning mapped directly to the problems I was solving at work.',
                'portrait' => 'assets/images/alumni/alumn-3.png',
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Sara Al Harthy',
                'country' => 'Oman',
                'program' => 'Master\'s — Cyber Security pathway',
                'previous_role' => 'IT Security Specialist',
                'current_role' => 'Cyber Risk Lead, enterprise',
                'quote' => 'I switched from technical delivery into risk leadership with a clearer strategic lens.',
                'portrait' => 'assets/images/alumni/alumn-5.png',
                'portrait_asset_id' => null,
            ],
        ]);
    }
};
