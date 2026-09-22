<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_overview.core_kicker', 'Learners');
        $this->migrator->add('mba_masters_overview.core_text', "and\nprofessionals");
        $this->migrator->add('mba_masters_career.show_section', true);
        $this->migrator->add('mba_masters_testimonials.show_section', true);

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
