<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('mba_masters_trust.stats', fn () => [
            ['value' => '4.9/5', 'label' => 'average student rating'],
            ['value' => '9,000+', 'label' => 'Learners Empowered'],
            ['value' => '100%', 'label' => 'online. No visa. No relocation.'],
            ['value' => '4,500', 'label' => 'Students Supported'],
            ['value' => '20+', 'label' => 'University Partners'],
        ]);

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
