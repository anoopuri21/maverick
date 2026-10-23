<?php

use App\Support\PublicContentCache;
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
            Schema::table('our_story_testimonials', function (Blueprint $table) {
                $table->json('pages')->nullable()->after('media_asset_id');
            });
        }

        $defaultSharedPages = [
            TestimonialPages::MBA_MASTERS_LANDING,
            TestimonialPages::OUR_STORY,
        ];

        // 1. Backfill any existing `our_story_testimonials` records that do not yet have `pages` assigned.
        DB::table('our_story_testimonials')
            ->whereNull('pages')
            ->update([
                'pages' => json_encode($defaultSharedPages),
            ]);

        // 2. Import legacy `mba_masters_testimonials.items` entries from `settings` into the unified table
        //    so existing Master's Landing quotes remain intact and are managed from Global Content -> Testimonials.
        if (Schema::hasTable('settings')) {
            $rawPayload = DB::table('settings')
                ->where('group', 'mba_masters_testimonials')
                ->where('name', 'items')
                ->value('payload');

            $legacyItems = is_string($rawPayload) && trim($rawPayload) !== ''
                ? json_decode($rawPayload, true)
                : null;

            if (is_array($legacyItems) && $legacyItems !== []) {
                $hasMediaAssets = Schema::hasTable('media_assets');
                $nextOrder = (int) (DB::table('our_story_testimonials')->max('sort_order') ?? 0) + 1;
                $now = now();

                $existingRows = DB::table('our_story_testimonials')
                    ->select('id', 'name', 'testimonial', 'pages')
                    ->get();

                foreach ($legacyItems as $legacyItem) {
                    if (! is_array($legacyItem)) {
                        continue;
                    }

                    $name = trim((string) ($legacyItem['name'] ?? ''));
                    $quote = trim((string) ($legacyItem['quote'] ?? ''));
                    $role = trim((string) ($legacyItem['role'] ?? ''));
                    $photo = trim((string) ($legacyItem['photo'] ?? '')) ?: null;

                    if ($name === '' && $quote === '') {
                        continue;
                    }

                    $normalizedQuote = mb_strtolower(trim(preg_replace('/\s+/u', ' ', strip_tags($quote)) ?? $quote));

                    $matchingRow = $existingRows->first(function ($row) use ($name, $normalizedQuote): bool {
                        $rowQuote = mb_strtolower(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $row->testimonial)) ?? ''));
                        if ($normalizedQuote !== '' && $rowQuote !== '') {
                            return $rowQuote === $normalizedQuote;
                        }

                        return mb_strtolower(trim((string) $row->name)) === mb_strtolower($name);
                    });

                    if ($matchingRow) {
                        $pages = TestimonialPages::normalize($matchingRow->pages ?? []);
                        if (! in_array(TestimonialPages::MBA_MASTERS_LANDING, $pages, true)) {
                            $pages[] = TestimonialPages::MBA_MASTERS_LANDING;
                            DB::table('our_story_testimonials')
                                ->where('id', $matchingRow->id)
                                ->update([
                                    'pages' => json_encode(array_values($pages)),
                                    'updated_at' => $now,
                                ]);
                        }

                        continue;
                    }

                    $position = null;
                    $organisation = null;
                    if ($role !== '') {
                        if (str_contains($role, '·')) {
                            [$left, $right] = array_map('trim', explode('·', $role, 2));
                            $position = $left !== '' ? $left : null;
                            $organisation = $right !== '' ? $right : null;
                        } else {
                            $position = $role;
                        }
                    }

                    $mediaAssetId = null;
                    if (! empty($legacyItem['photo_asset_id']) && $hasMediaAssets) {
                        $candidateId = (int) $legacyItem['photo_asset_id'];
                        if ($candidateId > 0 && DB::table('media_assets')->where('id', $candidateId)->exists()) {
                            $mediaAssetId = $candidateId;
                        }
                    }

                    $insertedId = DB::table('our_story_testimonials')->insertGetId([
                        'name' => $name !== '' ? $name : 'Student',
                        'position' => $position,
                        'organisation' => $organisation,
                        'country' => null,
                        'rating' => 5,
                        'testimonial' => $quote,
                        'photo' => $photo,
                        'media_asset_id' => $mediaAssetId,
                        'pages' => json_encode([TestimonialPages::MBA_MASTERS_LANDING]),
                        'sort_order' => $nextOrder++,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $existingRows->push((object) [
                        'id' => $insertedId,
                        'name' => $name !== '' ? $name : 'Student',
                        'testimonial' => $quote,
                        'pages' => json_encode([TestimonialPages::MBA_MASTERS_LANDING]),
                    ]);
                }
            }
        }

        try {
            PublicContentCache::flush();
        } catch (\Throwable) {
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('our_story_testimonials') && Schema::hasColumn('our_story_testimonials', 'pages')) {
            Schema::table('our_story_testimonials', function (Blueprint $table) {
                $table->dropColumn('pages');
            });
        }
    }
};
