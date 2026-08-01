<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('programs', 'image_url_asset_id')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->foreignId('image_url_asset_id')->nullable()
                    ->constrained('media_assets')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('programs', 'image_url_asset_id')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->dropConstrainedForeignId('image_url_asset_id');
            });
        }
    }
};
