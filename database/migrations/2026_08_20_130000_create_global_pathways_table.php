<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_pathways', function (Blueprint $table) {
            $table->id();
            // 'pathway-programs' | 'global-opportunities' (future types allowed)
            $table->string('type');
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->string('heading_italic')->nullable();
            $table->text('intro')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_url_asset_id')->nullable();
            $table->json('items')->nullable(); // [{title, desc, url, icon}]
            $table->json('seo')->nullable();   // meta_title, meta_description, etc.
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_pathways');
    }
};
