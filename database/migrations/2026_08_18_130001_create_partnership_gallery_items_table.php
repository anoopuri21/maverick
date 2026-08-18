<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('image_url')->nullable();
            $table->unsignedBigInteger('image_url_asset_id')->nullable();
            $table->string('category');
            $table->string('badge');
            $table->date('event_date')->nullable();
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->string('size')->default('medium');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_gallery_items');
    }
};
