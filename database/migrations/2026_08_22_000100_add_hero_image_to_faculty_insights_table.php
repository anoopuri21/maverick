<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faculty_insights', function (Blueprint $table) {
            if (! Schema::hasColumn('faculty_insights', 'hero_image_url')) {
                $table->string('hero_image_url')->nullable()->after('image_url_asset_id');
            }
            if (! Schema::hasColumn('faculty_insights', 'hero_image_url_asset_id')) {
                $table->foreignId('hero_image_url_asset_id')->nullable()
                    ->after('hero_image_url')
                    ->constrained('media_assets')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('faculty_insights', function (Blueprint $table) {
            if (Schema::hasColumn('faculty_insights', 'hero_image_url_asset_id')) {
                $table->dropConstrainedForeignId('hero_image_url_asset_id');
            }
            if (Schema::hasColumn('faculty_insights', 'hero_image_url')) {
                $table->dropColumn('hero_image_url');
            }
        });
    }
};
