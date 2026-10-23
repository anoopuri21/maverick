<?php

use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('our_story_testimonials')) {
            return;
        }

        // 1. Remove legacy placeholder quotes from `our_story_testimonials` and clear deprecated
        //    `mba_masters_testimonials.items` settings array so DB table `our_story_testimonials`
        //    is the single, exclusive source of truth controlled from Admin Panel.
        DB::table('our_story_testimonials')
            ->whereIn('name', [
                'MBA graduate',
                'MSc graduate',
                'Sara Al Maktoum',
                'James Okonkwo',
                'Priya Menon',
            ])
            ->delete();

        if (Schema::hasTable('settings')) {
            DB::table('settings')
                ->where('group', 'mba_masters_testimonials')
                ->where('name', 'items')
                ->update(['payload' => json_encode([])]);
        }

        // 2. Seed all 10 verified student testimonials + portraits into `media_assets` & `our_story_testimonials`.
        $dataFile = database_path('seeders/data/testimonials.php');
        if (! File::exists($dataFile)) {
            return;
        }

        $data = require $dataFile;
        $testimonials = $data['testimonials'] ?? [];
        if (! is_array($testimonials) || $testimonials === []) {
            return;
        }

        $defaultPages = [
            TestimonialPages::MBA_MASTERS_LANDING,
            TestimonialPages::OUR_STORY,
        ];

        $hasMediaAssets = Schema::hasTable('media_assets');
        $diskEnv = config('app.env', 'production');
        $now = now();

        foreach ($testimonials as $item) {
            $imagePath = ltrim((string) ($item['local_image'] ?? ''), '/');
            $fullLocalPath = public_path($imagePath);
            $mediaAssetId = null;

            if ($hasMediaAssets && $imagePath !== '') {
                $hash = File::exists($fullLocalPath)
                    ? hash_file('sha256', $fullLocalPath)
                    : hash('sha256', ($item['name'] ?? '') . '_' . ($item['image_filename'] ?? $imagePath));

                $existingAsset = DB::table('media_assets')->where('hash', $hash)->first();

                if (! $existingAsset) {
                    $fileSize = File::exists($fullLocalPath) ? File::size($fullLocalPath) : null;
                    $dimensions = File::exists($fullLocalPath) ? @getimagesize($fullLocalPath) : null;
                    $publicId = 'testimonials/' . pathinfo((string) ($item['image_filename'] ?? basename($imagePath)), PATHINFO_FILENAME);

                    if (DB::table('media_assets')->where('cloudinary_public_id', $publicId)->exists()) {
                        $publicId .= '_' . substr($hash, 0, 8);
                    }

                    $mediaAssetId = DB::table('media_assets')->insertGetId([
                        'hash' => $hash,
                        'original_name' => $item['image_filename'] ?? basename($imagePath),
                        'mime_type' => 'image/jpeg',
                        'size_bytes' => $fileSize,
                        'width' => $dimensions[0] ?? 800,
                        'height' => $dimensions[1] ?? 800,
                        'cloudinary_public_id' => $publicId,
                        'url' => '/' . $imagePath,
                        'folder' => 'testimonials',
                        'alt' => ($item['name'] ?? '') . ' - ' . ($item['position'] ?? ''),
                        'disk_env' => $diskEnv,
                        'used' => true,
                        'is_duplicate' => false,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    $mediaAssetId = $existingAsset->id;
                    DB::table('media_assets')
                        ->where('id', $mediaAssetId)
                        ->update([
                            'url' => '/' . $imagePath,
                            'used' => true,
                            'updated_at' => $now,
                        ]);
                }
            }

            $existingRow = DB::table('our_story_testimonials')
                ->where('name', $item['name'])
                ->first();

            $quoteText = trim((string) ($item['quote'] ?? strip_tags((string) ($item['testimonial'] ?? ''))));
            $photoUrl = $imagePath !== '' ? '/' . $imagePath : null;

            if ($existingRow) {
                $existingPages = TestimonialPages::normalize($existingRow->pages ?? []);
                $pagesToStore = $existingPages !== [] ? $existingPages : $defaultPages;

                DB::table('our_story_testimonials')
                    ->where('id', $existingRow->id)
                    ->update([
                        'organisation' => $existingRow->organisation ?: ($item['organisation'] ?? null),
                        'position' => $existingRow->position ?: ($item['position'] ?? null),
                        'country' => $existingRow->country ?: ($item['country'] ?? null),
                        'rating' => $existingRow->rating ?: ($item['rating'] ?? 5),
                        'testimonial' => $quoteText,
                        'photo' => $existingRow->photo ?: $photoUrl,
                        'media_asset_id' => $existingRow->media_asset_id ?: $mediaAssetId,
                        'pages' => json_encode($pagesToStore),
                        'sort_order' => $existingRow->sort_order ?: (int) ($item['sort_order'] ?? 0),
                        'deleted_at' => null,
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('our_story_testimonials')->insert([
                    'name' => $item['name'],
                    'organisation' => $item['organisation'] ?? null,
                    'position' => $item['position'] ?? null,
                    'country' => $item['country'] ?? null,
                    'rating' => (int) ($item['rating'] ?? 5),
                    'testimonial' => $quoteText,
                    'photo' => $photoUrl,
                    'media_asset_id' => $mediaAssetId,
                    'pages' => json_encode($defaultPages),
                    'sort_order' => (int) ($item['sort_order'] ?? 0),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        try {
            PublicContentCache::flush();
        } catch (\Throwable) {
        }
    }

    public function down(): void
    {
        // Preserve admin-managed records on rollback.
    }
};
