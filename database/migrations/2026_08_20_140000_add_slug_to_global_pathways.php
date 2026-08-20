<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('global_pathways', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('global_pathways', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
