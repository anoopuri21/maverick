<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship
            $table->morphs('seoable'); // Creates seoable_id + seoable_type
            
            // Basic SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index, follow')->nullable();
            
            // Open Graph (Facebook, LinkedIn, WhatsApp)
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image_url')->nullable();
            $table->string('og_type')->default('website')->nullable();
            
            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image_url')->nullable();
            
            // Structured Data (Schema.org JSON-LD)
            $table->longText('schema_json')->nullable();
            
            // Custom Scripts
            $table->longText('custom_head_scripts')->nullable(); // Analytics, pixels
            $table->longText('custom_body_scripts')->nullable(); // Body-end scripts
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metadata');
    }
};