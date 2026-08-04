<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Media Gallery — press / media coverage.
     */
    public function up(): void
    {
        Schema::create('media_press_mentions', function (Blueprint $table) {
            $table->id();
            $table->string('publication');
            $table->string('code')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('publication_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_press_mentions');
    }
};
