<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'our_story_sections.testimonials_intro' => 'Real stories from working professionals advancing their global careers with Maverick Business Academy.',
            'our_story_sections.show_testimonials' => true,
        ];

        foreach ($keys as $key => $default) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, $default);
            }
        }
    }
};
