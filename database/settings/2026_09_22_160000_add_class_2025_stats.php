<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_class.class_year_stats', [
            ['label' => 'MBA Students', 'value' => '979'],
            ['label' => 'Countries Represented', 'value' => '77'],
            ['label' => 'Pass Rate', 'value' => '98.70%'],
            ['label' => 'Average Age', 'value' => '33.7'],
            ['label' => 'Average Years of Professional Experience', 'value' => '11.2'],
        ]);

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
