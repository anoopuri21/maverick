<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_categories', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'program_categories_active_sort_idx');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'programs_active_sort_idx');
            $table->index('program_category_id', 'programs_category_id_idx');
            $table->index('university_partner_id', 'programs_university_partner_id_idx');
        });

        Schema::table('faculty_insights', function (Blueprint $table) {
            $table->index(['is_active', 'published_at'], 'faculty_insights_active_published_idx');
        });

        Schema::table('gup_partner_universities', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'gup_partners_active_sort_idx');
        });

        Schema::table('partnership_gallery_items', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'partnership_gallery_active_sort_idx');
            $table->index(['is_active', 'category'], 'partnership_gallery_active_category_idx');
            $table->index('event_date', 'partnership_gallery_event_date_idx');
        });

        Schema::table('media_gallery_photos', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'media_photos_active_sort_idx');
            $table->index(['is_active', 'category'], 'media_photos_active_category_idx');
        });

        Schema::table('media_gallery_videos', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'media_videos_active_sort_idx');
        });

        Schema::table('our_story_timelines', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'our_story_timelines_active_sort_idx');
        });

        Schema::table('our_story_awards', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'our_story_awards_active_sort_idx');
        });

        Schema::table('our_story_gallery_images', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'our_story_gallery_active_sort_idx');
            $table->index(['is_active', 'category'], 'our_story_gallery_active_category_idx');
        });

        Schema::table('our_story_testimonials', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'our_story_testimonials_active_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('program_categories', fn (Blueprint $t) => $t->dropIndex('program_categories_active_sort_idx'));
        Schema::table('programs', function (Blueprint $t) {
            $t->dropIndex('programs_active_sort_idx');
            $t->dropIndex('programs_category_id_idx');
            $t->dropIndex('programs_university_partner_id_idx');
        });
        Schema::table('faculty_insights', fn (Blueprint $t) => $t->dropIndex('faculty_insights_active_published_idx'));
        Schema::table('gup_partner_universities', fn (Blueprint $t) => $t->dropIndex('gup_partners_active_sort_idx'));
        Schema::table('partnership_gallery_items', function (Blueprint $t) {
            $t->dropIndex('partnership_gallery_active_sort_idx');
            $t->dropIndex('partnership_gallery_active_category_idx');
            $t->dropIndex('partnership_gallery_event_date_idx');
        });
        Schema::table('media_gallery_photos', function (Blueprint $t) {
            $t->dropIndex('media_photos_active_sort_idx');
            $t->dropIndex('media_photos_active_category_idx');
        });
        Schema::table('media_gallery_videos', fn (Blueprint $t) => $t->dropIndex('media_videos_active_sort_idx'));
        Schema::table('our_story_timelines', fn (Blueprint $t) => $t->dropIndex('our_story_timelines_active_sort_idx'));
        Schema::table('our_story_awards', fn (Blueprint $t) => $t->dropIndex('our_story_awards_active_sort_idx'));
        Schema::table('our_story_gallery_images', function (Blueprint $t) {
            $t->dropIndex('our_story_gallery_active_sort_idx');
            $t->dropIndex('our_story_gallery_active_category_idx');
        });
        Schema::table('our_story_testimonials', fn (Blueprint $t) => $t->dropIndex('our_story_testimonials_active_sort_idx'));
    }
};
