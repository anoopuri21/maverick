<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_today.badge', null);
        $this->migrator->add('our_story_today.heading', null);
        $this->migrator->add('our_story_today.description', null);
        $this->migrator->add('our_story_today.image_url', null);
    }
};
