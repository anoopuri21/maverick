<?php

namespace Database\Seeders;

use App\Models\MediaAsset;
use App\Models\OurStoryTestimonial;
use App\Settings\MbaMastersTestimonialsSettings;
use App\Settings\MbaMastersVideoTestimonialsSettings;
use App\Support\PublicContentCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TestimonialSeeder extends Seeder
{
    /**
     * Seed testimonials into the database and admin settings.
     *
     * Feeds:
     * 1. Our Story Page (DB table `our_story_testimonials`, managed in Admin -> Our Story Page -> Testimonials)
     * 2. Master Landing Page (Spatie Settings `mba_masters_testimonials`, managed in Admin -> MBA Masters — Proof -> Testimonials)
     * 3. Media Assets library (DB table `media_assets`, managed in Admin -> Media Library)
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

        $this->command?->info('Seeding ' . count($testimonials) . ' testimonials for Our Story and Master Landing pages...');

        $destinationDir = public_path('assets/images/testimonials');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        $settingsItems = [];

        foreach ($testimonials as $t) {
            $imagePath = $t['local_image'];
            $fullLocalPath = public_path($imagePath);

            // Attempt to download remote image if local image missing and URL available
            if (! File::exists($fullLocalPath) && ! empty($t['remote_image_url'])) {
                try {
                    $response = Http::timeout(10)->get($t['remote_image_url']);
                    if ($response->successful()) {
                        File::put($fullLocalPath, $response->body());
                        $this->command?->line("  [Image] Downloaded {$t['image_filename']} from Google Docs.");
                    }
                } catch (\Throwable $e) {
                    Log::warning("Could not download testimonial image for {$t['name']}: " . $e->getMessage());
                }
            }

            // Create or lookup MediaAsset record
            $mediaAssetId = null;
            if (Schema::hasTable('media_assets')) {
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
                        // Ensure unique cloudinary_public_id if already taken
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
                            'url' => $imagePath,
                            'folder' => 'our-story/testimonials',
                            'alt' => $t['name'] . ' - ' . $t['course'],
                            'disk_env' => $diskEnv,
                            'used' => true,
                            'is_duplicate' => false,
                        ]);
                    } else {
                        $mediaAsset->update([
                            'url' => $imagePath,
                            'used' => true,
                        ]);
                    }

                    $mediaAssetId = $mediaAsset->id;
                } catch (\Throwable $e) {
                    $this->command?->warn("  [MediaAsset] Warning for {$t['name']}: " . $e->getMessage());
                }
            }

            // 1. Insert/Update OurStoryTestimonial (DB table `our_story_testimonials`)
            OurStoryTestimonial::updateOrCreate(
                ['name' => $t['name']],
                [
                    'organisation' => $t['organisation'],
                    'position' => $t['position'],
                    'country' => $t['country'],
                    'rating' => $t['rating'] ?? 5,
                    'testimonial' => $t['testimonial'],
                    'photo' => $imagePath,
                    'media_asset_id' => $mediaAssetId,
                    'sort_order' => $t['sort_order'],
                    'is_active' => true,
                ]
            );

            // Collect item for Master Landing Page Settings
            $settingsItems[] = [
                'name' => $t['name'],
                'role' => $t['course'] . ' · ' . $t['university'],
                'quote' => $t['quote'] ?? strip_tags($t['testimonial']),
                'photo' => $imagePath,
                'photo_asset_id' => $mediaAssetId,
            ];

            $this->command?->line("  ✓ [#{$t['sort_order']}] {$t['name']} ({$t['country']}) -> OurStoryTestimonial");
        }

        // 2. Feed Master Landing Page Settings (`MbaMastersTestimonialsSettings`)
        try {
            $mbaSettings = app(MbaMastersTestimonialsSettings::class);
            $mbaSettings->label = 'Testimonials';
            $mbaSettings->heading = 'What Our Students Say';
            $mbaSettings->intro = 'Real experiences from working professionals and graduates across our global academic partner network.';
            $mbaSettings->show_section = true;
            $mbaSettings->items = $settingsItems;
            $mbaSettings->save();

            $this->command?->info('  ✓ Master Landing Page testimonials settings updated (' . count($settingsItems) . ' quotes).');
        } catch (\Throwable $e) {
            $this->command?->warn('  [Settings] Warning updating MbaMastersTestimonialsSettings: ' . $e->getMessage());
        }

        // 3. Update Master Landing Page Video Testimonials if available
        if (! empty($videos)) {
            try {
                $videoSettings = app(MbaMastersVideoTestimonialsSettings::class);
                $existingVideos = $videoSettings->videos ?? [];
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
                $this->command?->info('  ✓ Master Landing Page video shorts updated (' . count($mergedVideos) . ' videos).');
            } catch (\Throwable $e) {
                // Non-critical
            }
        }

        // 4. Flush caches
        try {
            PublicContentCache::flush(PublicContentCache::OUR_STORY);
            PublicContentCache::flush();
            $this->command?->info('  ✓ Public content cache flushed.');
        } catch (\Throwable $e) {
            // Ignore if cache flushing fails
        }

        $this->command?->info('✓ Testimonials seed completed successfully!');
    }
}
