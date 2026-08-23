<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_alumni.index', '11');
        $this->migrator->add('mba_masters_alumni.label', 'Alumni network');
        $this->migrator->add('mba_masters_alumni.heading', 'Our Alumni Work at Top Companies');
        $this->migrator->add(
            'mba_masters_alumni.intro',
            'Graduates placing across aviation, energy, finance, consulting and government — a global professional network you join from day one.'
        );
        $this->migrator->add(
            'mba_masters_alumni.trust_line',
            'Join our growing network of industry leaders worldwide'
        );
    }
};
