<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'mba_masters_trust.label',
            fn () => 'Rated 4.9 out of 5 by people who finished the degree'
        );
        $this->migrator->update(
            'mba_masters_trust.quote',
            fn () => 'One of the best decisions I have made. The degree is genuinely university-awarded, and my employer in Abu Dhabi had zero questions about its validity.'
        );
        $this->migrator->update(
            'mba_masters_trust.quote_attribution',
            fn () => 'Rajesh Menon, MBA graduate, Abu Dhabi'
        );
        $this->migrator->update('mba_masters_trust.stats', fn () => [
            ['value' => '4.9/5', 'label' => 'Average student rating'],
            ['value' => '9,000+', 'label' => 'Learners Empowered'],
            ['value' => '100%', 'label' => 'Online. No visa. No relocation.'],
            ['value' => '4,500', 'label' => 'Students Supported'],
            ['value' => '20+', 'label' => 'University Partners'],
        ]);

        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'More than 30 specializations, 100% online, from institutions you can put on a CV. Maverick Business Academy London offers routes with Rushford Business School, a private Swiss business school and not a Swiss university; Girne American University in North Cyprus; the University for the Creative Arts in the UK with Rushford on the Dual MBA; the University of Wolverhampton; and the University of the West of Scotland for the MBA in International Business. The award pathway and current authorization are confirmed in writing before you enroll. The Dual MBA, UCA with Rushford, is that written pathway. Maverick does not describe UCA as listing Rushford as a direct campus partner.'
        );

        $this->migrator->update(
            'mba_masters_partners.intro',
            fn () => 'Maverick Business Academy London offers Master\'s programs awarded by the institutions named below. Rushford Business School is a private Swiss business school, not a Swiss university, and its status is confirmed in writing for each program. The routes also include Girne American University, the University for the Creative Arts, the University of Wolverhampton, and the University of the West of Scotland. The award pathway and current authorization are confirmed in writing before you enroll. The Dual MBA, UCA with Rushford, is that written pathway. Maverick does not describe UCA as listing Rushford as a direct campus partner.'
        );

        $this->migrator->update('mba_masters_class.label', fn () => 'Cohort');

        $this->migrator->update(
            'mba_masters_fees.intro',
            fn () => 'Fees depend on the awarding institution, program, entry route, and intake. Totals are usually AED 16,000 to 40,000, and the exact figure is confirmed in writing. Your written quotation lists tuition, registration, VAT where it applies, final-project charges, attestation charges, instalment dates, and refund terms.'
        );

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
