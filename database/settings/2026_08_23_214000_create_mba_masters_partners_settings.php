<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_partners.index', '13');
        $this->migrator->add('mba_masters_partners.label', 'University partners');
        $this->migrator->add('mba_masters_partners.heading', 'Awarded by recognised partner universities');
        $this->migrator->add(
            'mba_masters_partners.intro',
            'Online MBA and Master’s pathways delivered with internationally recognised awarding partners — Switzerland, Cyprus, and the UK.'
        );
        $this->migrator->add(
            'mba_masters_partners.trust_line',
            'Rushford · GAU · UCA · University of Wolverhampton — and more across our academic network'
        );
    }
};
