<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Populate repeaters from the legacy positional fields so the
        // homepage section renders identically after the refactor.
        $get = fn (string $key) => \Illuminate\Support\Facades\DB::table('settings')
            ->where('group', 'global_opportunities')->where('name', $key)
            ->value('payload');

        $decode = fn ($v) => is_string($v) ? json_decode($v, true) : $v;

        $opportunities = [];
        foreach (['opp1', 'opp2', 'opp3', 'opp4'] as $p) {
            $title = $decode($get($p.'_title'));
            if ($title) {
                $opportunities[] = [
                    'title' => $title,
                    'desc'  => $decode($get($p.'_desc')) ?? null,
                    'url'   => $decode($get($p.'_url')) ?? null,
                ];
            }
        }

        $pathways = [];
        foreach (['path1', 'path2', 'path3', 'path4'] as $p) {
            $title = $decode($get($p.'_title'));
            if ($title) {
                $pathways[] = [
                    'title' => $title,
                    'desc'  => $decode($get($p.'_desc')) ?? null,
                    'url'   => $decode($get($p.'_url')) ?? null,
                ];
            }
        }

        $this->migrator->add('global_opportunities.opportunities', $opportunities);
        $this->migrator->add('global_opportunities.pathways', $pathways);
    }
};
