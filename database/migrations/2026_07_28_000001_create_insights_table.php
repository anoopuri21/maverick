<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');
            $table->string('featured_image_url')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->json('categories'); // e.g. ["blogs"], ["news"], ["blogs","news"]
            $table->json('tags')->nullable();
            $table->string('author_name')->default('Maverick Business Academy');
            $table->string('author_avatar_url')->nullable();
            $table->string('author_bio', 500)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('reading_time_minutes')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->json('extra')->nullable(); // future category-specific fields (event date, student name, etc.)
            $table->timestamps();

            $table->index('published_at');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insights');
    }
};
