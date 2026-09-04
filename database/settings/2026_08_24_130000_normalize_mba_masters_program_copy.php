<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $payload = DB::table('settings')
            ->where('group', 'mba_masters_masters')
            ->where('name', 'universities')
            ->value('payload');

        $universities = is_string($payload) ? json_decode($payload, true) : $payload;
        $universities = is_array($universities) ? $universities : [];

        foreach ($universities as &$university) {
            foreach ($university['programs'] ?? [] as &$program) {
                $title = trim((string) ($program['title'] ?? ''));
                $program['title'] = preg_replace(
                    '/\s*\(listed under Master[’\']s \/ MBA options\)$/iu',
                    '',
                    $title
                ) ?: $title;
            }
            unset($program);
        }
        unset($university);

        $this->migrator->update('mba_masters_masters.universities', fn () => $universities);
    }
};
