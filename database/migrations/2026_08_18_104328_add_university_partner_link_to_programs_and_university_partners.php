<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link each Program to its single offering UniversityPartner,
     * and give UniversityPartner a slug (initials) for unique URL routing.
     */
    public function up(): void
    {
        // 1. UniversityPartner gets a unique slug used for program-URL disambiguation.
        Schema::table('university_partners', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // 2. Program gets a single university_partner FK (1 program = 1 university).
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('university_partner_id')
                ->nullable()
                ->after('program_category_id')
                ->constrained('university_partners')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('university_partner_id');
        });

        Schema::table('university_partners', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
