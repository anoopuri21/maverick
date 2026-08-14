<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-driven programme detail content.
     * Each block is stored as JSON on the programmes row (no extra tables).
     * Lists → arrays; paragraphs → RichEditor HTML strings.
     */
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->json('highlights')->nullable();
            $table->json('recognition')->nullable();
            $table->json('snapshot')->nullable();
            $table->json('benefits')->nullable();
            $table->json('learning')->nullable();
            $table->json('careers')->nullable();
            $table->json('structure')->nullable();
            $table->json('support')->nullable();
            $table->json('university')->nullable();
            $table->json('accreditation_groups')->nullable();
            $table->json('testimonials')->nullable();
            $table->json('fees')->nullable();
            $table->json('reviews')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn([
                'highlights',
                'recognition',
                'snapshot',
                'benefits',
                'learning',
                'careers',
                'structure',
                'support',
                'university',
                'accreditation_groups',
                'testimonials',
                'fees',
                'reviews',
            ]);
        });
    }
};
