<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Backfill: move legacy partner_university string + university JSON into
     * UniversityPartner rows, link programs via university_partner_id, and
     * generate unique slugs. Idempotent — safe to re-run.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // Collect all programs that still carry a legacy partner_university string.
            $programs = DB::table('programs')
                ->whereNotNull('partner_university')
                ->where('partner_university', '!=', '')
                ->get();

            foreach ($programs as $program) {
                $name = trim($program->partner_university);

                // Find existing partner by name, else create.
                $partner = DB::table('university_partners')
                    ->where('name', $name)
                    ->first();

                if (! $partner) {
                    $slug = $this->uniqueSlug($name, 'university_partners');
                    $partnerId = DB::table('university_partners')->insertGetId([
                        'name' => $name,
                        'slug' => $slug,
                        'country' => '',
                        'sort_order' => 0,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $partnerId = $partner->id;
                    // Ensure slug exists
                    if (empty($partner->slug)) {
                        DB::table('university_partners')
                            ->where('id', $partnerId)
                            ->update(['slug' => $this->uniqueSlug($name, 'university_partners', $partnerId)]);
                    }
                }

                // Merge legacy university JSON (name/description/establishment/image)
                $legacy = json_decode($program->university ?? '[]', true) ?? [];
                $legacyRow = is_array($legacy) ? ($legacy[0] ?? []) : [];

                if (! empty($legacyRow) && is_array($legacyRow)) {
                    $description = $legacyRow['description'] ?? null;
                    $establishment = $legacyRow['establishment'] ?? null;
                    if ($description || $establishment) {
                        DB::table('university_partners')
                            ->where('id', $partnerId)
                            ->update([
                                'description' => $description,
                                'updated_at' => now(),
                            ]);
                    }
                }

                // Link program → university partner.
                DB::table('programs')
                    ->where('id', $program->id)
                    ->update(['university_partner_id' => $partnerId, 'updated_at' => now()]);
            }
        });
    }

    public function down(): void
    {
        // Reverse: clear university_partner_id back to null.
        DB::table('programs')->update(['university_partner_id' => null]);
    }

    private function uniqueSlug(string $name, string $table, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'university';
        $slug = $base;
        $i = 1;
        while (DB::table($table)->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
};
