<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_hero.heading', null);
        $this->migrator->add('our_story_hero.subtitle', null);
        $this->migrator->add('our_story_hero.description', null);
        $this->migrator->add('our_story_hero.cta_label', null);
        $this->migrator->add('our_story_hero.cta_url', null);
        $this->migrator->add('our_story_hero.image_url', null);
    }
};
