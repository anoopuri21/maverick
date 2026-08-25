<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $base = 'assets/images/mba-masters-landing/mba/';

        $this->migrator->update(
            'mba_masters_mba.stage_image',
            fn () => $base.'mba-stage.jpg'
        );

        $payload = DB::table('settings')
            ->where('group', 'mba_masters_mba')
            ->where('name', 'tabs')
            ->value('payload');

        $tabs = is_string($payload) ? json_decode($payload, true) : $payload;
        if (! is_array($tabs) || $tabs === []) {
            return;
        }

        $imagesByTab = [
            'general' => [
                $base.'general-mba.jpg',
                $base.'business-management-mba.jpg',
            ],
            'specialized' => [$base.'specialized-mba.jpg'],
            'executive' => [$base.'executive-mba.jpg'],
            'global' => [$base.'global-mba.jpg'],
        ];

        $changed = false;
        foreach ($tabs as &$tab) {
            $tabKey = strtolower(trim((string) ($tab['key'] ?? '')));
            $tabImages = $imagesByTab[$tabKey] ?? [];

            foreach ($tab['universities'] ?? [] as $universityIndex => &$university) {
                if (! isset($tabImages[$universityIndex])) {
                    continue;
                }

                $image = $tabImages[$universityIndex];
                if (($university['image'] ?? null) !== $image || ($university['image_asset_id'] ?? null) !== null) {
                    $changed = true;
                }
                $university['image'] = $image;
                $university['image_asset_id'] = null;
            }
            unset($university);
        }
        unset($tab);

        if ($changed) {
            $this->migrator->update(
                'mba_masters_mba.tabs',
                fn () => $tabs
            );
        }
    }
};
