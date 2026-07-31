<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('our_story_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('testimonial');
            $table->string('photo')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('our_story_testimonials');
    }
};
