<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_faq.index', '16');
        $this->migrator->add('mba_masters_faq.label', 'FAQ');
        $this->migrator->add('mba_masters_faq.heading', 'Questions applicants ask first');
        $this->migrator->add('mba_masters_faq.items', [
            [
                'question' => 'Are the degrees internationally recognised?',
                'answer' => 'Yes. Pathways are awarded by partner universities such as Rushford Business School, Girne American University (GAU), University for the Creative Arts (UCA), and University of Wolverhampton — depending on the programme you choose.',
            ],
            [
                'question' => 'Can I study while working full-time in the UAE?',
                'answer' => 'That is the design intent. Live sessions, recorded catch-up, and advisor support are built for professionals who cannot pause their careers.',
            ],
            [
                'question' => 'How long do Online MBA and Master’s pathways take?',
                'answer' => 'Most routes complete in roughly 10–12 months, subject to the specific university pathway and your study pace. Admissions will confirm timelines for your shortlist.',
            ],
            [
                'question' => 'What are the entry requirements?',
                'answer' => 'Typically a bachelor’s degree and relevant work experience. Some pathways accept mature applicants with professional experience — share your profile for an eligibility check.',
            ],
            [
                'question' => 'How do fees and scholarships work?',
                'answer' => 'Fees vary by university and programme. Request a fee sheet and scholarship eligibility check from admissions — we publish indicative ranges and update them as partners confirm.',
            ],
            [
                'question' => 'Is the learning fully online?',
                'answer' => 'Yes for the Online MBA and Master’s routes on this page: live classes online, recorded materials, and remote academic support. No UAE campus relocation required.',
            ],
        ]);
    }
};
