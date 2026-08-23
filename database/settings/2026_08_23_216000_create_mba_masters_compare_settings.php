<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_compare.index', '15');
        $this->migrator->add('mba_masters_compare.label', 'Online vs traditional');
        $this->migrator->add('mba_masters_compare.heading', 'Why working professionals choose online');
        $this->migrator->add(
            'mba_masters_compare.intro',
            'A clear side-by-side of what changes when you study online with Maverick — without the campus relocation tax.'
        );
        $this->migrator->add('mba_masters_compare.col_online', 'Online with Maverick');
        $this->migrator->add('mba_masters_compare.col_traditional', 'Traditional campus');
        $this->migrator->add('mba_masters_compare.rows', [
            [
                'criterion' => 'Schedule',
                'online' => 'Live + recorded — fit around full-time work',
                'traditional' => 'Fixed daytime / term timetable',
            ],
            [
                'criterion' => 'Location',
                'online' => 'Study from UAE (or anywhere)',
                'traditional' => 'Relocate or long commute',
            ],
            [
                'criterion' => 'Duration',
                'online' => 'Focused 10–12 month pathways',
                'traditional' => 'Often 18–24+ months',
            ],
            [
                'criterion' => 'Cost of living',
                'online' => 'No campus relocation costs',
                'traditional' => 'Housing, travel, living abroad',
            ],
            [
                'criterion' => 'Career continuity',
                'online' => 'Keep your role and income',
                'traditional' => 'Career pause is common',
            ],
            [
                'criterion' => 'Awarding body',
                'online' => 'Recognised partner universities',
                'traditional' => 'Single campus institution',
            ],
        ]);
        $this->migrator->add('mba_masters_compare.cta_label', 'Check Eligibility');
        $this->migrator->add('mba_masters_compare.cta_url', '#mlp-enquire');
    }
};
