<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_testimonials.index', '14');
        $this->migrator->add('mba_masters_testimonials.label', 'Student voices');
        $this->migrator->add('mba_masters_testimonials.heading', 'What working professionals say');
        $this->migrator->add(
            'mba_masters_testimonials.intro',
            'Placeholders until graduate permission — then we swap in verified quotes from Our Story testimonials.'
        );
        $this->migrator->add('mba_masters_testimonials.items', [
            [
                'name' => 'Sara Al Maktoum',
                'role' => 'Operations Lead · Dubai',
                'quote' => 'The online rhythm fitted around my role. Advisors kept enrolment and fees clear from day one.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
            [
                'name' => 'James Okonkwo',
                'role' => 'Finance Manager · Abu Dhabi',
                'quote' => 'I needed a recognised MBA without pausing my career. Live sessions plus recordings made that possible.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
            [
                'name' => 'Priya Menon',
                'role' => 'HR Business Partner · Sharjah',
                'quote' => 'Clear pathway, solid partner universities, and a team that answered the hard questions on recognition.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
        ]);
    }
};
