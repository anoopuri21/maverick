<?php

declare(strict_types=1);

use App\Support\TestimonialPages;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('our_story_testimonials')) {
            return;
        }

        if (! Schema::hasColumn('our_story_testimonials', 'pages')) {
            Schema::table('our_story_testimonials', static function (Blueprint $table): void {
                $table->json('pages')->nullable()->after('media_asset_id');
            });
        }

        DB::table('our_story_testimonials')
            ->whereNull('pages')
            ->update([
                'pages' => json_encode([
                    TestimonialPages::MBA_MASTERS_LANDING,
                    TestimonialPages::OUR_STORY,
                ]),
            ]);
    }

    public function down(): void
    {
        if (Schema::hasTable('our_story_testimonials') && Schema::hasColumn('our_story_testimonials', 'pages')) {
            Schema::table('our_story_testimonials', static function (Blueprint $table): void {
                $table->dropColumn('pages');
            });
        }
    }
};
