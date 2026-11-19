<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('who_we_are.cta_url')) {
            return;
        }

        $this->migrator->update('who_we_are.cta_url', function ($value) {
            $path = trim((string) $value);

            return $path === '/about-us/our-story' ? '/our-story' : $value;
        });
    }
};
