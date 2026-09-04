<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Do not publish placeholder names/quotes. Admin can add approved testimonials later.
        $this->migrator->update('mba_masters_testimonials.items', fn () => []);
        $this->migrator->update('mba_masters_testimonials.intro', fn () => '');
    }
};
