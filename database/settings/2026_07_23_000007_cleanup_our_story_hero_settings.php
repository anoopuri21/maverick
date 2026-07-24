<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('our_story_hero.subtitle');
        $this->migrator->delete('our_story_hero.cta_label');
        $this->migrator->delete('our_story_hero.cta_url');
    }
};
