<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_metadata', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->change();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
        });

        Schema::table('insights', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('seo_metadata', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->change();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
        });

        Schema::table('insights', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
        });
    }
};
