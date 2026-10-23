<?php

namespace Database\Seeders;

use App\Models\MediaAsset;
use App\Models\OurStoryTestimonial;
use App\Settings\MbaMastersTestimonialsSettings;
use App\Settings\MbaMastersVideoTestimonialsSettings;
use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TestimonialSeeder extends Seeder
{
    /**
     * Seed the 10 verified student testimonials into `our_story_testimonials`
     * (single DB source of truth managed under Filament Admin -> Global Content -> Testimonials).
     */
    public function run(): void
    {
        $dataFile = __DIR__ . '/data/testimonials.php';
        if (! File::exists($dataFile)) {
            $this->command?->error("Testimonials data file not found at: {$dataFile}");
            return;
        }

        $data = require $dataFile;
        $testimonials = $data['testimonials'] ?? [];
        $videos = $data['video_testimonials'] ?? [];

        $defaultPages = [
            TestimonialPages::MBA_MASTERS_LANDING,
            TestimonialPages::OUR_STORY,
        ];

        $this->command?->info('Seeding ' . count($testimonials) . ' testimonials into DB table `our_story_testimonials`...');

        $destinationDir = public_path('assets/images/testimonials');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        OurStoryTestimonial::withTrashed()->forceDelete();

        foreach ($testimonials as $t) {
            $imagePath = ltrim((string) ($t['local_image'] ?? ''), '/');
            $fullLocalPath = public_path($imagePath);

            if (! File::exists($fullLocalPath) && ! empty($t['remote_image_url'])) {
                try {
                    $response = Http::timeout(10)->get($t['remote_image_url']);
                    if ($response->successful()) {
                        File::put($fullLocalPath, $response->body());
                    }
                } catch (\Throwable $e) {
                    Log::warning("Could not download testimonial image for {$t['name']}: " . $e->getMessage());
                }
            }

            $mediaAssetId = null;
            if (Schema::hasTable('media_assets') && $imagePath !== '') {
                try {
                    $hash = File::exists($fullLocalPath)
                        ? hash_file('sha256', $fullLocalPath)
                        : hash('sha256', $t['name'] . '_' . $t['image_filename']);

                    $diskEnv = config('app.env', 'production');
                    $fileSize = File::exists($fullLocalPath) ? File::size($fullLocalPath) : null;
                    $dimensions = File::exists($fullLocalPath) ? @getimagesize($fullLocalPath) : null;

                    $mediaAsset = MediaAsset::withoutGlobalScopes()->where('hash', $hash)->first();

                    if (! $mediaAsset) {
                        $publicId = 'testimonials/' . pathinfo($t['image_filename'], PATHINFO_FILENAME);
                        if (MediaAsset::withoutGlobalScopes()->where('cloudinary_public_id', $publicId)->exists()) {
                            $publicId .= '_' . substr($hash, 0, 8);
                        }

                        $mediaAsset = MediaAsset::create([
                            'hash' => $hash,
                            'original_name' => $t['image_filename'],
                            'mime_type' => 'image/jpeg',
                            'size_bytes' => $fileSize,
                            'width' => $dimensions[0] ?? 800,
                            'height' => $dimensions[1] ?? 800,
                            'cloudinary_public_id' => $publicId,
                            'url' => '/' . $imagePath,
                            'folder' => 'testimonials',
                            'alt' => $t['name'] . ' - ' . $t['course'],
                            'disk_env' => $diskEnv,
                            'used' => true,
                            'is_duplicate' => false,
                        ]);
                    } else {
                        $mediaAsset->update([
                            'url' => '/' . $imagePath,
                            'used' => true,
                        ]);
                    }

                    $mediaAssetId = $mediaAsset->id;
                } catch (\Throwable $e) {
                    $this->command?->warn("  [MediaAsset] Warning for {$t['name']}: " . $e->getMessage());
                }
            }

            OurStoryTestimonial::create([
                'name' => $t['name'],
                'organisation' => $t['organisation'],
                'position' => $t['position'],
                'country' => $t['country'],
                'rating' => $t['rating'] ?? 5,
                'testimonial' => trim((string) ($t['quote'] ?? strip_tags((string) $t['testimonial']))),
                'photo' => $imagePath !== '' ? '/' . $imagePath : null,
                'media_asset_id' => $mediaAssetId,
                'pages' => $t['pages'] ?? $defaultPages,
                'sort_order' => $t['sort_order'],
                'is_active' => true,
            ]);
        }

        // Ensure legacy settings array does not duplicate DB rows
        try {
            $mbaSettings = app(MbaMastersTestimonialsSettings::class);
            $mbaSettings->items = [];
            $mbaSettings->save();
        } catch (\Throwable) {
        }

        if (! empty($videos)) {
            try {
                $videoSettings = app(MbaMastersVideoTestimonialsSettings::class);
                $mergedVideos = [];
                foreach ($videos as $v) {
                    $mergedVideos[] = [
                        'name' => $v['name'],
                        'role' => $v['role'],
                        'category' => $v['category'] ?? 'STUDENT',
                        'video_url' => $v['video_url'],
                        'thumbnail' => null,
                        'thumbnail_asset_id' => null,
                    ];
                }
                $videoSettings->videos = $mergedVideos;
                $videoSettings->save();
            } catch (\Throwable) {
            }
        }

        PublicContentCache::flush();
        $this->command?->info('✓ Seeded ' . count($testimonials) . ' student testimonials into `our_story_testimonials`.');
    }
}
