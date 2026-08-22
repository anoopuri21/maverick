<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('faculty_insights') && ! $this->hasIndex('faculty_insights', 'faculty_insights_badge_active_idx')) {
            Schema::table('faculty_insights', function (Blueprint $table) {
                $table->index(['badge', 'is_active'], 'faculty_insights_badge_active_idx');
            });
        }

        if (Schema::hasTable('university_partners') && Schema::hasColumn('university_partners', 'slug') && ! $this->hasIndex('university_partners', 'university_partners_slug_idx')) {
            Schema::table('university_partners', function (Blueprint $table) {
                $table->index('slug', 'university_partners_slug_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('faculty_insights') && $this->hasIndex('faculty_insights', 'faculty_insights_badge_active_idx')) {
            Schema::table('faculty_insights', fn (Blueprint $t) => $t->dropIndex('faculty_insights_badge_active_idx'));
        }

        if (Schema::hasTable('university_partners') && $this->hasIndex('university_partners', 'university_partners_slug_idx')) {
            Schema::table('university_partners', fn (Blueprint $t) => $t->dropIndex('university_partners_slug_idx'));
        }
    }

    private function hasIndex(string $table, string $name): bool
    {
        return collect(Schema::getIndexes($table))->contains(
            fn (array $index) => ($index['name'] ?? '') === $name
        );
    }
};
