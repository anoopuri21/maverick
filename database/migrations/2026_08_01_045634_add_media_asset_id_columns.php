<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('insights', 'featured_image_url_asset_id')) {
            Schema::table('insights', function (Blueprint $table) {
                $table->foreignId('featured_image_url_asset_id')->nullable()
                    ->constrained('media_assets')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('insights', 'featured_image_url_asset_id')) {
            Schema::table('insights', function (Blueprint $table) {
                $table->dropConstrainedForeignId('featured_image_url_asset_id');
            });
        }
    }
};
