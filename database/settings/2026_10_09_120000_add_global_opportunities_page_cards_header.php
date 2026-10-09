<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'global_opportunities_page.cards_label' => '',
            'global_opportunities_page.cards_heading' => '',
            'global_opportunities_page.cards_heading_italic' => '',
        ];

        foreach ($keys as $key => $default) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, $default);
            }
        }
    }
};
