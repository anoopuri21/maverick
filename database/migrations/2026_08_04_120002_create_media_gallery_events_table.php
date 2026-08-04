<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Media Gallery — recent events.
     */
    public function up(): void
    {
        Schema::create('media_gallery_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('image_url_asset_id')->nullable()
                ->constrained('media_assets')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_gallery_events');
    }
};
