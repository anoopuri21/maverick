<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $p1 = $this->settingValue('global_access_points', 'story_p1')
            ?? 'From the Gulf to the wider world, the Access Points network keeps the learning conversation open across borders.';
        $p2 = $this->settingValue('global_access_points', 'story_p2')
            ?? 'Select a country to bring its point into focus, then drag the globe to explore the wider constellation.';

        $body = trim($this->asHtmlParagraph($p1).$this->asHtmlParagraph($p2));

        if (! $this->migrator->exists('global_access_points.story_body')) {
            $this->migrator->add('global_access_points.story_body', $body);
        }

        foreach (['global_access_points.story_p1', 'global_access_points.story_p2'] as $key) {
            if ($this->migrator->exists($key)) {
                $this->migrator->delete($key);
            }
        }
    }

    private function settingValue(string $group, string $name): ?string
    {
        $row = DB::table('settings')->where('group', $group)->where('name', $name)->first();
        if (! $row) {
            return null;
        }

        $decoded = json_decode($row->payload, true);

        return is_string($decoded) && trim($decoded) !== '' ? $decoded : null;
    }

    private function asHtmlParagraph(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if (str_contains($value, '<')) {
            return $value;
        }

        return '<p>'.e($value).'</p>';
    }
};
