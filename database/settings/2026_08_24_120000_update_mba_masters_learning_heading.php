<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'mba_masters_learning.heading',
            fn () => 'A flexible learning model'
        );
    }
};
