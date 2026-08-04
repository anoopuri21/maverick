<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Media Gallery — featured videos.
     */
    public function up(): void
    {
        Schema::create('media_gallery_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('video_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->foreignId('thumbnail_url_asset_id')->nullable()
                ->constrained('media_assets')->nullOnDelete();
            $table->string('duration')->nullable();
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_gallery_videos');
    }
};
