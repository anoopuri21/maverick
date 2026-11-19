<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'event_start_time')) {
                $table->string('event_start_time')->nullable();
            }
            if (! Schema::hasColumn('events', 'event_end_time')) {
                $table->string('event_end_time')->nullable();
            }
            if (! Schema::hasColumn('events', 'gallery_videos')) {
                $table->json('gallery_videos')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'event_start_time')) {
                $table->dropColumn('event_start_time');
            }
            if (Schema::hasColumn('events', 'event_end_time')) {
                $table->dropColumn('event_end_time');
            }
            if (Schema::hasColumn('events', 'gallery_videos')) {
                $table->dropColumn('gallery_videos');
            }
        });
    }
};
