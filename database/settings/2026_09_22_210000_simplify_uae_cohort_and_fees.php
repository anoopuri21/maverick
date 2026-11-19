<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('mba_masters_class.intro', function ($intro) {
            return str_replace(
                'When a group project starts, it sounds like a regional business meeting because that is who is on the call.',
                'A group project puts you in a room with people who already run teams across the Gulf.',
                (string) $intro
            );
        });

        $this->migrator->update('mba_masters_fees.rows', function ($rows) {
            $rows = json_decode(json_encode($rows ?? []), true);
            if (! is_array($rows)) {
                return [];
            }

            $rename = [
                'MBA, Rushford (14 Specializations)' => 'MBA, Rushford — 14 specializations',
                'Executive MBA, Girne American University' => 'EMBA, Girne American University',
                'MBA in International Business, UWS' => 'MBA in International Business, University of the West of Scotland',
            ];

            $next = [];
            foreach ($rows as $row) {
                $program = (string) ($row['program'] ?? '');
                if ($program === 'MSc (Rushford, Girne) and LLM (Wolverhampton)') {
                    $masters = $row;
                    $masters['program'] = 'Master\'s degrees, Rushford and Girne';
                    $laws = $row;
                    $laws['program'] = 'Master of Laws, Wolverhampton';
                    $next[] = $masters;
                    $next[] = $laws;
                    continue;
                }
                if (isset($rename[$program])) {
                    $row['program'] = $rename[$program];
                }
                $next[] = $row;
            }

            return $next;
        });

        $this->migrator->update('mba_masters_fees.blocks', function ($blocks) {
            $blocks = json_decode(json_encode($blocks ?? []), true);
            if (! is_array($blocks)) {
                return [];
            }

            $drop = [
                'What your written quotation shows',
                'Monthly AED installments',
                'Monthly AED instalments',
            ];

            return array_values(array_filter(
                $blocks,
                fn ($block) => ! in_array((string) ($block['title'] ?? ''), $drop, true)
            ));
        });

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
