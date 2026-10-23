<?php

declare(strict_types=1);

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
use Throwable;

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

        $destinationDir = public_path('assets/images/testimonials');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        OurStoryTestimonial::withTrashed()->forceDelete();

        foreach ($testimonials as $item) {
            $imagePath = ltrim((string) ($item['local_image'] ?? ''), '/');
            $fullLocalPath = public_path($imagePath);

            if (! File::exists($fullLocalPath) && ! empty($item['remote_image_url'])) {
                $this->downloadPortraitImage($item['remote_image_url'], $fullLocalPath, (string) $item['name']);
            }

            $mediaAssetId = $this->syncMediaAsset($item, $imagePath, $fullLocalPath);

            OurStoryTestimonial::create([
                'name' => $item['name'],
                'organisation' => $item['organisation'] ?? null,
                'position' => $item['position'] ?? null,
                'country' => $item['country'] ?? null,
                'rating' => (int) ($item['rating'] ?? 5),
                'testimonial' => OurStoryTestimonial::sanitizeQuote(
                    (string) ($item['quote'] ?? $item['testimonial'] ?? '')
                ),
                'photo' => $imagePath !== '' ? '/' . $imagePath : null,
                'media_asset_id' => $mediaAssetId,
                'pages' => $item['pages'] ?? $defaultPages,
                'sort_order' => (int) ($item['sort_order'] ?? 0),
                'is_active' => true,
            ]);
        }

        $this->clearLegacySettingsQuotes();
        $this->syncVideoTestimonials($videos);

        PublicContentCache::flush();
        $this->command?->info('✓ Seeded ' . count($testimonials) . ' student testimonials into `our_story_testimonials`.');
    }

    private function downloadPortraitImage(string $remoteUrl, string $destinationPath, string $studentName): void
    {
        try {
            $response = Http::timeout(10)->get($remoteUrl);
            if ($response->successful()) {
                File::put($destinationPath, $response->body());
            }
        } catch (Throwable $e) {
            Log::warning("Could not download testimonial image for {$studentName}: " . $e->getMessage());
        }
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function syncMediaAsset(array $item, string $imagePath, string $fullLocalPath): ?int
    {
        if (! Schema::hasTable('media_assets') || $imagePath === '') {
            return null;
        }

        try {
            $filename = (string) ($item['image_filename'] ?? basename($imagePath));
            $hash = File::exists($fullLocalPath)
                ? hash_file('sha256', $fullLocalPath)
                : hash('sha256', ($item['name'] ?? '') . '_' . $filename);

            $fileSize = File::exists($fullLocalPath) ? File::size($fullLocalPath) : null;
            $dimensions = File::exists($fullLocalPath) ? @getimagesize($fullLocalPath) : null;

            $mediaAsset = MediaAsset::withoutGlobalScopes()->where('hash', $hash)->first();

            if (! $mediaAsset) {
                $publicId = 'testimonials/' . pathinfo($filename, PATHINFO_FILENAME);
                if (MediaAsset::withoutGlobalScopes()->where('cloudinary_public_id', $publicId)->exists()) {
                    $publicId .= '_' . substr($hash, 0, 8);
                }

                $mediaAsset = MediaAsset::create([
                    'hash' => $hash,
                    'original_name' => $filename,
                    'mime_type' => 'image/jpeg',
                    'size_bytes' => $fileSize,
                    'width' => $dimensions[0] ?? 800,
                    'height' => $dimensions[1] ?? 800,
                    'cloudinary_public_id' => $publicId,
                    'url' => '/' . $imagePath,
                    'folder' => 'testimonials',
                    'alt' => ($item['name'] ?? '') . ' - ' . ($item['course'] ?? ''),
                    'disk_env' => config('app.env', 'production'),
                    'used' => true,
                    'is_duplicate' => false,
                ]);
            } else {
                $mediaAsset->update([
                    'url' => '/' . $imagePath,
                    'used' => true,
                ]);
            }

            return (int) $mediaAsset->id;
        } catch (Throwable $e) {
            $this->command?->warn("  [MediaAsset] Warning for {$item['name']}: " . $e->getMessage());

            return null;
        }
    }

    private function clearLegacySettingsQuotes(): void
    {
        try {
            $mbaSettings = app(MbaMastersTestimonialsSettings::class);
            $mbaSettings->items = [];
            $mbaSettings->save();
        } catch (Throwable) {
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $videos
     */
    private function syncVideoTestimonials(array $videos): void
    {
        if ($videos === []) {
            return;
        }

        try {
            $videoSettings = app(MbaMastersVideoTestimonialsSettings::class);
            $mergedVideos = [];

            foreach ($videos as $video) {
                $mergedVideos[] = [
                    'name' => $video['name'],
                    'role' => $video['role'],
                    'category' => $video['category'] ?? 'STUDENT',
                    'video_url' => $video['video_url'],
                    'thumbnail' => null,
                    'thumbnail_asset_id' => null,
                ];
            }

            $videoSettings->videos = $mergedVideos;
            $videoSettings->save();
        } catch (Throwable) {
        }
    }
}
