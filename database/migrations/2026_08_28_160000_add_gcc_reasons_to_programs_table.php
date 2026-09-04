<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (! Schema::hasColumn('programs', 'gcc_heading')) {
                $table->string('gcc_heading')->nullable()->after('support');
            }
            if (! Schema::hasColumn('programs', 'gcc_reasons')) {
                $table->json('gcc_reasons')->nullable()->after('gcc_heading');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            foreach (['gcc_heading', 'gcc_reasons'] as $column) {
                if (Schema::hasColumn('programs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
