<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faculty_insights', function (Blueprint $table) {
            if (! Schema::hasColumn('faculty_insights', 'country')) {
                $table->string('country')->nullable()->after('faculty_role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('faculty_insights', function (Blueprint $table) {
            if (Schema::hasColumn('faculty_insights', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};
