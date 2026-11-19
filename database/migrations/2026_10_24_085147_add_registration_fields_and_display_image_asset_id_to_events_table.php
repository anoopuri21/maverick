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
            if (! Schema::hasColumn('events', 'display_image_asset_id')) {
                $table->foreignId('display_image_asset_id')->nullable()->after('display_image')->constrained('media_assets')->nullOnDelete();
            }
            if (! Schema::hasColumn('events', 'registration_fields')) {
                $table->json('registration_fields')->nullable()->after('link_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'display_image_asset_id')) {
                $table->dropForeign(['display_image_asset_id']);
                $table->dropColumn('display_image_asset_id');
            }
            if (Schema::hasColumn('events', 'registration_fields')) {
                $table->dropColumn('registration_fields');
            }
        });
    }
};
