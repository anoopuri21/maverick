<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('blog_hero.eyebrow', null);
        $this->migrator->add('blog_hero.heading', null);
        $this->migrator->add('blog_hero.description', null);
        $this->migrator->add('blog_hero.image_url', null);
    }
};
