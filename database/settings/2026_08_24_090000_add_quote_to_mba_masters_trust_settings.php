<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('mba_masters_trust.quote')) {
            $this->migrator->add(
                'mba_masters_trust.quote',
                'Every number is a person who chose to keep moving.'
            );
        }
    }
};
