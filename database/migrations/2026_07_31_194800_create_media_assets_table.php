<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('cloudinary_public_id');
            $table->string('url');
            $table->string('folder');
            $table->string('alt')->nullable();
            $table->string('disk_env')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['hash', 'disk_env']);
            $table->index('folder');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
    }
};
