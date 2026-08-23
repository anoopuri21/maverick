<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gup_partner_universities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('abbreviation', 12)->nullable();
            $table->string('country');
            $table->string('flag_emoji', 8)->nullable();
            $table->text('recognition')->nullable();
            $table->string('logo_url')->nullable();
            $table->unsignedBigInteger('logo_url_asset_id')->nullable();
            $table->string('cta_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gup_partner_universities');
    }
};
