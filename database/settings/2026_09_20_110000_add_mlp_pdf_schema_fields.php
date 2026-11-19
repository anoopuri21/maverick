<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('mba_masters_trust.quote_attribution')) {
            $this->migrator->add('mba_masters_trust.quote_attribution', null);
        }

        if (! $this->migrator->exists('mba_masters_fees.blocks')) {
            $this->migrator->add('mba_masters_fees.blocks', []);
        }

        if (! $this->migrator->exists('mba_masters_partners.checklist')) {
            $this->migrator->add('mba_masters_partners.checklist', []);
        }

        if (! $this->migrator->exists('mba_masters_compare.blocks')) {
            $this->migrator->add('mba_masters_compare.blocks', []);
        }
    }
};
