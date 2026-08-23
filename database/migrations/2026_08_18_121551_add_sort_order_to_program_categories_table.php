<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add sort_order so admin can control category ordering (used by NavMenu
     * and the navbar mega-menu). Default 0, fill existing rows with id order.
     */
    public function up(): void
    {
        Schema::table('program_categories', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('is_active');
        });

        // Backfill: order existing categories by creation order (id).
        \Illuminate\Support\Facades\DB::table('program_categories')
            ->orderBy('id')
            ->get()
            ->each(function ($row, $index) {
                \Illuminate\Support\Facades\DB::table('program_categories')
                    ->where('id', $row->id)
                    ->update(['sort_order' => $index]);
            });
    }

    public function down(): void
    {
        Schema::table('program_categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
