<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Media Gallery — photo collage items.
     */
    public function up(): void
    {
        Schema::create('media_gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->string('image_url')->nullable();
            $table->foreignId('image_url_asset_id')->nullable()
                ->constrained('media_assets')->nullOnDelete();
            $table->string('caption')->nullable();
            $table->string('category')->nullable();
            $table->string('size')->default('medium'); // small | medium | large
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_gallery_photos');
    }
};
