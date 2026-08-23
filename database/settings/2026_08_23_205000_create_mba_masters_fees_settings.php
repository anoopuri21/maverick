<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_fees.index', '08');
        $this->migrator->add('mba_masters_fees.label', 'Fees & payment');
        $this->migrator->add('mba_masters_fees.heading', 'Online MBA & Master\'s Fees in UAE');
        $this->migrator->add(
            'mba_masters_fees.intro',
            'Transparent fee ranges with flexible payment options. Exact fees vary by university and specialization — admissions will confirm your plan.'
        );
        $this->migrator->add(
            'mba_masters_fees.note',
            'Placeholder ranges pending client approval. Initial registration, monthly instalments and scholarship support available where eligible.'
        );
        $this->migrator->add('mba_masters_fees.stage_image', 'assets/images/programs/enquire-seminar.jpg');
        $this->migrator->add('mba_masters_fees.stage_image_asset_id', null);
        $this->migrator->add('mba_masters_fees.rows', [
            [
                'program' => 'Online MBA (General / Specialized)',
                'duration' => '10–12 months / 1 year',
                'mode' => 'Online · Flexible',
                'fee' => 'From AED XX,XXX*',
                'payment' => 'Registration + monthly instalments',
            ],
            [
                'program' => 'Executive MBA routes',
                'duration' => '12–18 months',
                'mode' => 'Online · Executive pace',
                'fee' => 'From AED XX,XXX*',
                'payment' => 'Flexible payment · scholarship check',
            ],
            [
                'program' => 'Master\'s / MSc pathways',
                'duration' => '1 year routes available',
                'mode' => 'Online · Assignment-based',
                'fee' => 'From AED XX,XXX*',
                'payment' => 'Registration + instalments',
            ],
            [
                'program' => 'LLM (University of Wolverhampton)',
                'duration' => 'As per university schedule',
                'mode' => 'Online',
                'fee' => 'On request*',
                'payment' => 'Speak to an advisor',
            ],
        ]);
        $this->migrator->add('mba_masters_fees.cta_primary_label', 'Request Fee Details');
        $this->migrator->add('mba_masters_fees.cta_primary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_fees.cta_secondary_label', 'Get Scholarship Eligibility Check');
        $this->migrator->add('mba_masters_fees.cta_secondary_url', '#mlp-enquire');
    }
};
