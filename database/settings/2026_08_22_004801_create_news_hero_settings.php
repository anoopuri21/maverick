<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('news_hero.eyebrow', null);
        $this->migrator->add('news_hero.heading', null);
        $this->migrator->add('news_hero.description', null);
        $this->migrator->add('news_hero.image_url', null);
    }
};
