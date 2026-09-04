<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('university_partners', function (Blueprint $table) {
            $table->string('campus_image_url')->nullable()->after('logo_url');
            $table->foreignId('campus_image_url_asset_id')
                ->nullable()
                ->after('logo_url_asset_id')
                ->constrained('media_assets')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('university_partners', function (Blueprint $table) {
            $table->dropConstrainedForeignId('campus_image_url_asset_id');
            $table->dropColumn('campus_image_url');
        });
    }
};
