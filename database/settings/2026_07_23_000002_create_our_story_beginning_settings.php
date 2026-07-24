<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_beginning.badge', null);
        $this->migrator->add('our_story_beginning.heading', null);
        $this->migrator->add('our_story_beginning.paragraph_1', null);
        $this->migrator->add('our_story_beginning.paragraph_2', null);
        $this->migrator->add('our_story_beginning.image_url', null);
    }
};
