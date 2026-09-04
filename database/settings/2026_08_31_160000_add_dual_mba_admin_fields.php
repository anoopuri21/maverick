<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('dual_mba_hero.background_image_alt', null);
        $this->migrator->add('dual_mba_hero.visual_image_alt', null);
        $this->migrator->add('dual_mba_employers.counter_value', null);
    }
};
