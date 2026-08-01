<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('university_partners', 'logo_url_asset_id')) {
            Schema::table('university_partners', function (Blueprint $table) {
                $table->foreignId('logo_url_asset_id')->nullable()
                    ->constrained('media_assets')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('university_partners', 'logo_url_asset_id')) {
            Schema::table('university_partners', function (Blueprint $table) {
                $table->dropConstrainedForeignId('logo_url_asset_id');
            });
        }
    }
};
