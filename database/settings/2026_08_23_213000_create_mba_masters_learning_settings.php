<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_learning.index', '12');
        $this->migrator->add('mba_masters_learning.label', 'Learning experience');
        $this->migrator->add('mba_masters_learning.heading', 'Built for working professionals');
        $this->migrator->add(
            'mba_masters_learning.intro',
            'Online and flexible study designed around your job — live sessions, recorded catch-up, and a clear path from enrolment to completion.'
        );
        $this->migrator->add('mba_masters_learning.plate_image', 'assets/images/homepage/mba-management.jpg');
        $this->migrator->add('mba_masters_learning.plate_image_asset_id', null);
        $this->migrator->add('mba_masters_learning.plate_caption', 'Live sessions · Flexible catch-up · Career-ready pace');
        $this->migrator->add('mba_masters_learning.points', [
            [
                'title' => 'Live + on-demand',
                'text' => 'Join interactive live classes, then revisit recordings when work runs late.',
            ],
            [
                'title' => 'Weekend-friendly rhythm',
                'text' => 'A schedule that respects full-time roles — progress without pausing your career.',
            ],
            [
                'title' => 'Advisor-backed onboarding',
                'text' => 'From enrolment to first session, academic guidance keeps you on track.',
            ],
            [
                'title' => '10–12 month pathways',
                'text' => 'Focused routes for Online MBA and Master’s that stay realistic for busy professionals.',
            ],
        ]);
        $this->migrator->add('mba_masters_learning.cta_primary_label', 'Check Eligibility');
        $this->migrator->add('mba_masters_learning.cta_primary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_learning.cta_secondary_label', 'Speak to an Advisor');
        $this->migrator->add('mba_masters_learning.cta_secondary_url', '#mlp-enquire');
    }
};
