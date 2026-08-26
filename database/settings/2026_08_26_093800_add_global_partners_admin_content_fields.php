<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'global_partners_hero.scroll_hint' => 'Scroll to explore',
            'global_partners_cards.cta_label' => 'Explore Programs',
            'global_partners_cards.recognition_label' => 'Recognition',
            'global_partners_overview.image_alt' => 'Partnership',
            'global_partners_benefits.main_image_alt' => 'Students',
            'global_partners_benefits.secondary_image_alt' => 'Students walking',
            'global_partners_journey.filter_all_label' => 'All',
            'global_partners_map.label' => 'Global Network',
            'global_partners_map.heading_line1' => 'Our Global',
            'global_partners_map.heading_line2' => 'Academic Network',
        ];

        foreach ($keys as $key => $default) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, $default);
            }
        }
    }
};
