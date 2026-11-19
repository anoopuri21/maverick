<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'voices_testimonials.show_section' => true,
            'voices_testimonials.label' => 'Testimonials',
            'voices_testimonials.heading' => 'What Our Students Say',
            'voices_testimonials.intro' => 'Real experiences from working professionals and graduates across our global academic partner network.',
            'voices_testimonials.stack_caption' => 'The experience, in their words.',
            'voices_testimonials.pages' => [
                [
                    'key' => 'mba-masters-landing',
                    'label' => "Master's Landing Page (/online-mba-masters-uae)",
                ],
                [
                    'key' => 'our-story',
                    'label' => 'Our Story Page (/our-story)',
                ],
            ],
            'mba_masters_testimonials.stack_caption' => 'The experience, in their words.',
            'our_story_sections.testimonials_stack_caption' => 'The experience, in their words.',
        ];

        foreach ($keys as $key => $default) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, $default);
            }
        }
    }
};
