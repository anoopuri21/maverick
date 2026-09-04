<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'our_story_hero.eyebrow',
            'our_story_hero.subtitle',
            'our_story_hero.cta_label',
            'our_story_hero.cta_url',
            'our_story_hero.scroll_hint',
            'our_story_impact.badge',
            'our_story_vision.badge',
            'our_story_sections.journey_badge',
            'our_story_sections.journey_heading',
            'our_story_sections.gallery_badge',
            'our_story_sections.gallery_heading',
            'our_story_sections.testimonials_badge',
            'our_story_sections.testimonials_heading',
        ];

        foreach ($keys as $key) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, null);
            }
        }
    }
};
