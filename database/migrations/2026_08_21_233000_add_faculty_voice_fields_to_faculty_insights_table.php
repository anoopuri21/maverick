<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faculty_insights', function (Blueprint $table) {
            if (! Schema::hasColumn('faculty_insights', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('badge');
            }
            if (! Schema::hasColumn('faculty_insights', 'content')) {
                $table->longText('content')->nullable()->after('excerpt');
            }
            if (! Schema::hasColumn('faculty_insights', 'pull_quote')) {
                $table->text('pull_quote')->nullable()->after('content');
            }
            if (! Schema::hasColumn('faculty_insights', 'faculty_name')) {
                $table->string('faculty_name')->nullable()->after('pull_quote');
            }
            if (! Schema::hasColumn('faculty_insights', 'faculty_role')) {
                $table->string('faculty_role')->nullable()->after('faculty_name');
            }
            if (! Schema::hasColumn('faculty_insights', 'faculty_bio')) {
                $table->text('faculty_bio')->nullable()->after('faculty_role');
            }
            if (! Schema::hasColumn('faculty_insights', 'faculty_avatar_url')) {
                $table->string('faculty_avatar_url')->nullable()->after('faculty_bio');
            }
            if (! Schema::hasColumn('faculty_insights', 'faculty_avatar_url_asset_id')) {
                $table->foreignId('faculty_avatar_url_asset_id')->nullable()
                    ->after('faculty_avatar_url')
                    ->constrained('media_assets')->nullOnDelete();
            }
            if (! Schema::hasColumn('faculty_insights', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('faculty_insights', 'reading_time_minutes')) {
                $table->unsignedSmallInteger('reading_time_minutes')->nullable()->after('published_at');
            }
            if (! Schema::hasColumn('faculty_insights', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('reading_time_minutes');
            }
            if (! Schema::hasColumn('faculty_insights', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (! Schema::hasColumn('faculty_insights', 'og_image_url')) {
                $table->string('og_image_url')->nullable()->after('meta_description');
            }
            if (! Schema::hasColumn('faculty_insights', 'og_image_url_asset_id')) {
                $table->foreignId('og_image_url_asset_id')->nullable()
                    ->after('og_image_url')
                    ->constrained('media_assets')->nullOnDelete();
            }
        });

        DB::table('faculty_insights')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)')]);
    }

    public function down(): void
    {
        Schema::table('faculty_insights', function (Blueprint $table) {
            if (Schema::hasColumn('faculty_insights', 'og_image_url_asset_id')) {
                $table->dropConstrainedForeignId('og_image_url_asset_id');
            }
            if (Schema::hasColumn('faculty_insights', 'faculty_avatar_url_asset_id')) {
                $table->dropConstrainedForeignId('faculty_avatar_url_asset_id');
            }

            $columns = [
                'excerpt',
                'content',
                'pull_quote',
                'faculty_name',
                'faculty_role',
                'faculty_bio',
                'faculty_avatar_url',
                'published_at',
                'reading_time_minutes',
                'meta_title',
                'meta_description',
                'og_image_url',
            ];

            $existing = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('faculty_insights', $column)));

            if ($existing) {
                $table->dropColumn($existing);
            }
        });
    }
};
