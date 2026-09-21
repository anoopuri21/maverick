<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('university_partners', function (Blueprint $table) {
            $table->json('recognition_logos')->nullable()->after('recognition');
        });

        $programs = DB::table('programs')
            ->whereNotNull('university_partner_id')
            ->whereNotNull('recognition')
            ->get(['university_partner_id', 'recognition']);

        $grouped = [];

        foreach ($programs as $program) {
            $rows = json_decode($program->recognition, true);

            if (! is_array($rows) || $rows === []) {
                continue;
            }

            $rows = array_values(array_filter($rows, fn ($row) => is_array($row) && $this->rowHasContent($row)));

            if ($rows === []) {
                continue;
            }

            $grouped[$program->university_partner_id][] = $rows;
        }

        foreach ($grouped as $universityId => $lists) {
            usort($lists, fn ($a, $b) => count($b) <=> count($a));

            $merged = [];
            $seen = [];

            foreach ($lists as $list) {
                foreach ($list as $row) {
                    $name = trim((string) ($row['name'] ?? ''));
                    $logo = trim((string) ($row['logo'] ?? ''));
                    $key = mb_strtolower($name).'|'.$logo;

                    if (isset($seen[$key])) {
                        continue;
                    }

                    $seen[$key] = true;
                    $merged[] = [
                        'name' => $row['name'] ?? '',
                        'logo' => $row['logo'] ?? null,
                        'note' => $row['note'] ?? null,
                    ];
                }
            }

            if ($merged === []) {
                continue;
            }

            DB::table('university_partners')
                ->where('id', $universityId)
                ->update([
                    'recognition_logos' => json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('university_partners', function (Blueprint $table) {
            $table->dropColumn('recognition_logos');
        });
    }

    private function rowHasContent(array $row): bool
    {
        return filled($row['name'] ?? null) || filled($row['logo'] ?? null);
    }
};
