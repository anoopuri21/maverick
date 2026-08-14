<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-driven programme detail content.
     * Each block is stored as JSON on the programmes row (no extra tables).
     * Lists → arrays; paragraphs → RichEditor HTML strings.
     *
     * Idempotent: only adds a column if it does not already exist, so the
     * migration is safe to re-run on any environment (including production).
     */
    public function up(): void
    {
        $columns = [
            'highlights',
            'recognition',
            'snapshot',
            'benefits',
            'learning',
            'careers',
            'structure',
            'support',
            'university',
            'accreditation_groups',
            'testimonials',
            'fees',
            'reviews',
        ];

        Schema::table('programs', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (Schema::hasColumn('programs', $column)) {
                    continue;
                }
                $table->json($column)->nullable();
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'highlights',
            'recognition',
            'snapshot',
            'benefits',
            'learning',
            'careers',
            'structure',
            'support',
            'university',
            'accreditation_groups',
            'testimonials',
            'fees',
            'reviews',
        ];

        Schema::table('programs', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (! Schema::hasColumn('programs', $column)) {
                    continue;
                }
                $table->dropColumn($column);
            }
        });
    }
};
