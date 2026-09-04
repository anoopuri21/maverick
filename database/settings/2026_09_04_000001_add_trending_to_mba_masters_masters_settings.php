<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('mba_masters_masters.trending')) {
            $this->migrator->add('mba_masters_masters.trending', [
                ['label' => 'BA (Hons) Management', 'percent' => 55],
                ['label' => 'MBA (Regular & Top-up)', 'percent' => 82],
                ['label' => 'MBA in Healthcare Management', 'percent' => 64],
                ['label' => 'MBA in Quality Management', 'percent' => 48],
                ['label' => 'MBA in Finance', 'percent' => 70],
                ['label' => 'MBA in Project & Operations Management', 'percent' => 52],
                ['label' => 'MBA in Strategic HRM & Leadership', 'percent' => 45],
                ['label' => 'Executive MBA', 'percent' => 60],
            ]);
        }
    }
};
