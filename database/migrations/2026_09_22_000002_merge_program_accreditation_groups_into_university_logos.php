<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $programs = DB::table('programs')
            ->whereNotNull('university_partner_id')
            ->whereNotNull('accreditation_groups')
            ->get(['university_partner_id', 'accreditation_groups']);

        $extras = [];

        foreach ($programs as $program) {
            $groups = json_decode($program->accreditation_groups, true);

            if (! is_array($groups)) {
                continue;
            }

            foreach ($groups as $group) {
                if (! is_array($group)) {
                    continue;
                }

                foreach ($group['items'] ?? [] as $item) {
                    if (! is_array($item) || ! $this->rowHasContent($item)) {
                        continue;
                    }

                    $extras[$program->university_partner_id][] = [
                        'name' => $item['name'] ?? '',
                        'logo' => $item['logo'] ?? null,
                        'note' => null,
                    ];
                }
            }
        }

        foreach ($extras as $universityId => $rows) {
            $partner = DB::table('university_partners')
                ->where('id', $universityId)
                ->first(['id', 'recognition_logos']);

            if (! $partner) {
                continue;
            }

            $existing = json_decode($partner->recognition_logos ?? '[]', true);

            if (! is_array($existing)) {
                $existing = [];
            }

            $seen = [];

            foreach ($existing as $row) {
                if (is_array($row)) {
                    $seen[$this->key($row)] = true;
                }
            }

            $changed = false;

            foreach ($rows as $row) {
                $key = $this->key($row);

                if (isset($seen[$key])) {
                    continue;
                }

                $seen[$key] = true;
                $existing[] = $row;
                $changed = true;
            }

            if (! $changed) {
                continue;
            }

            DB::table('university_partners')
                ->where('id', $universityId)
                ->update([
                    'recognition_logos' => json_encode(array_values($existing), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
        }
    }

    public function down(): void
    {
        // Append-only copy. programs.accreditation_groups is left in place.
    }

    private function rowHasContent(array $row): bool
    {
        return filled($row['name'] ?? null) || filled($row['logo'] ?? null);
    }

    private function key(array $row): string
    {
        return mb_strtolower(trim((string) ($row['name'] ?? ''))).'|'.trim((string) ($row['logo'] ?? ''));
    }
};
