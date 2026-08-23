<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_final.index', '17');
        $this->migrator->add('mba_masters_final.label', 'Next step');
        $this->migrator->add('mba_masters_final.heading', 'Ready to shape your future?');
        $this->migrator->add(
            'mba_masters_final.intro',
            'Check eligibility, fees, and start dates with an admissions advisor — or jump back to the enquiry form above.'
        );
        $this->migrator->add('mba_masters_final.plate_image', 'assets/images/edutainment/cta-cinematic.jpg');
        $this->migrator->add('mba_masters_final.plate_image_asset_id', null);
        $this->migrator->add('mba_masters_final.cta_primary_label', 'Apply Now');
        $this->migrator->add('mba_masters_final.cta_primary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_final.cta_secondary_label', 'Speak to an Advisor');
        $this->migrator->add('mba_masters_final.cta_secondary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_final.show_form', true);
        $this->migrator->add('mba_masters_final.form_title', 'Second chance to enquire');
    }
};
