<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_vision.heading', null);
        $this->migrator->add('our_story_vision.description', null);
        $this->migrator->add('our_story_vision.background_image_url', null);
        $this->migrator->add('our_story_vision.cta_label', null);
        $this->migrator->add('our_story_vision.cta_url', null);
    }
};
