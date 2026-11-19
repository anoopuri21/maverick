<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $heading = trim((string) $this->settingValue('mba_masters_partners', 'heading'));
        $suffix = 'University Partners';
        $line1 = 'International MBA Degrees from Our';
        $line2 = $suffix;

        if ($heading !== '') {
            if (str_ends_with($heading, $suffix)) {
                $line1 = trim(substr($heading, 0, -strlen($suffix)));
                $line2 = $suffix;
            } else {
                $line1 = $heading;
                $line2 = $suffix;
            }
        }

        if (! $this->migrator->exists('mba_masters_partners.heading_line1')) {
            $this->migrator->add('mba_masters_partners.heading_line1', $line1);
        }

        if (! $this->migrator->exists('mba_masters_partners.heading_line2')) {
            $this->migrator->add('mba_masters_partners.heading_line2', $line2);
        }
    }

    private function settingValue(string $group, string $name): ?string
    {
        $row = DB::table('settings')->where('group', $group)->where('name', $name)->first();

        if (! $row || ! isset($row->payload)) {
            return null;
        }

        $value = json_decode($row->payload, true);

        return is_string($value) ? $value : null;
    }
};
