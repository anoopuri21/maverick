<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('mba_masters_masters.trending_title')) {
            $this->migrator->add('mba_masters_masters.trending_title', 'Trending|Specialisations');
        }
    }
};
