<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove legacy redundant columns now that university is a proper relation.
     * Data was backfilled into university_partners by the prior migration.
     */
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'partner_university')) {
                $table->dropColumn('partner_university');
            }
            if (Schema::hasColumn('programs', 'university')) {
                $table->dropColumn('university');
            }
        });

        Schema::table('university_partners', function (Blueprint $table) {
            if (Schema::hasColumn('university_partners', 'programs')) {
                $table->dropColumn('programs');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('partner_university')->nullable();
            $table->json('university')->nullable();
        });
        Schema::table('university_partners', function (Blueprint $table) {
            $table->json('programs')->nullable();
        });
    }
};
