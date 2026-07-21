<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('program_category_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // Basic Info
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('partner_university')->nullable();
            $table->string('duration')->nullable();
            $table->string('level')->nullable();
            
            // Content
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            
            // Media
            $table->string('image_url')->nullable();
            
            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};