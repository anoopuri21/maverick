<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $get = fn (string $key) => \Illuminate\Support\Facades\DB::table('settings')
            ->where('group', 'global_opportunities')->where('name', $key)
            ->value('payload');

        $decode = fn ($v) => is_string($v) ? json_decode($v, true) : $v;

        $pathways = $decode($get('pathways')) ?? [];
        $opportunities = $decode($get('opportunities')) ?? [];

        $pathways = array_map(function ($item) {
            if (! is_array($item)) {
                return $item;
            }

            $item['slug'] = trim((string) ($item['url'] ?? $item['slug'] ?? ''), '/');
            unset($item['url']);

            return $item;
        }, $pathways);

        $opportunities = array_map(function ($item) {
            if (! is_array($item)) {
                return $item;
            }

            $item['slug'] = trim((string) ($item['url'] ?? $item['slug'] ?? ''), '/');
            unset($item['url']);

            return $item;
        }, $opportunities);

        $this->migrator->update('global_opportunities.pathways', fn () => $pathways);
        $this->migrator->update('global_opportunities.opportunities', fn () => $opportunities);
    }
};
