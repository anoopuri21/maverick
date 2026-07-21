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
        Schema::table('university_partners', function (Blueprint $table) {
            $table->string('city')->nullable()->after('country_code');
            $table->decimal('latitude', 10, 7)->nullable()->after('city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->boolean('is_hub')->default(false)->after('longitude');
            $table->string('recognition')->nullable()->after('description');
            $table->json('programs')->nullable()->after('recognition');
        });
    }

    public function down(): void
    {
        Schema::table('university_partners', function (Blueprint $table) {
            $table->dropColumn(['city', 'latitude', 'longitude', 'is_hub', 'recognition', 'programs']);
        });
    }
};
