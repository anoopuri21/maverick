<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_journey.index', '05');
        $this->migrator->add('mba_masters_journey.label', 'Admission journey');
        $this->migrator->add('mba_masters_journey.heading', 'From enquiry to your first live session');
        $this->migrator->add(
            'mba_masters_journey.intro',
            'A clear six-step path — guided by admissions so you know what happens next at every stage.'
        );
        $this->migrator->add('mba_masters_journey.steps', [
            [
                'title' => 'Enquiry',
                'text' => 'Share your goals, preferred program and start timeline. We respond with next steps.',
            ],
            [
                'title' => 'Admission counselling',
                'text' => 'A focused 30-minute session covering eligibility, specialization fit, fees and payment options.',
            ],
            [
                'title' => 'Decide stream',
                'text' => 'Confirm MBA or Master\'s pathway and specialization with your counsellor.',
            ],
            [
                'title' => 'Registration',
                'text' => 'Complete registration and payment setup to secure your place on the cohort.',
            ],
            [
                'title' => 'Orientation',
                'text' => 'Onboarding to the learning platform, academic expectations and support channels.',
            ],
            [
                'title' => 'Start live session',
                'text' => 'Join your first live class and begin the program with your cohort.',
            ],
        ]);
        $this->migrator->add('mba_masters_journey.cta_label', 'Start your enquiry');
        $this->migrator->add('mba_masters_journey.cta_url', '#mlp-enquire');
    }
};
