<?php

namespace App\Console\Commands;

use App\Models\MediaAsset;
use App\Models\OurStoryTestimonial;
use App\Settings\MbaMastersTestimonialsSettings;
use App\Settings\MbaMastersVideoTestimonialsSettings;
use App\Support\PublicContentCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SeedTestimonialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testimonials:seed
                            {--download-from-doc : Attempt to download original images from Google Doc URLs}
                            {--force : Force execution without confirmation in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the first 10 student testimonials into DB and Admin Panel for Our Story and Master Landing Page';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===========================================================');
        $this->info('  Maverick Business Academy — Testimonials Seeder');
        $this->info('===========================================================');
        $this->line('');

        $dataFile = database_path('seeders/data/testimonials.php');
        if (! File::exists($dataFile)) {
            $this->error("Error: Testimonials data file not found at: {$dataFile}");
            return self::FAILURE;
        }

        $data = require $dataFile;
        $testimonials = $data['testimonials'] ?? [];
        $videos = $data['video_testimonials'] ?? [];

        if (empty($testimonials)) {
            $this->error('Error: No testimonials found in data file.');
            return self::FAILURE;
        }

        $downloadFromDoc = $this->option('download-from-doc');

        $this->line("Found <comment>" . count($testimonials) . "</comment> testimonials ready to seed.");
        $this->line('');

        $destinationDir = public_path('assets/images/testimonials');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        $tableRows = [];
        $settingsItems = [];
        $seededCount = 0;

        foreach ($testimonials as $t) {
            $imagePath = $t['local_image'];
            $fullLocalPath = public_path($imagePath);
            $imageStatus = 'Local Exists';

            // Optional download from Google Docs URL
            if (($downloadFromDoc || ! File::exists($fullLocalPath)) && ! empty($t['remote_image_url'])) {
                try {
                    $this->line("  Connecting to download image for: <info>{$t['name']}</info>...");
                    $response = Http::timeout(15)->get($t['remote_image_url']);
                    if ($response->successful()) {
                        File::put($fullLocalPath, $response->body());
                        $imageStatus = 'Downloaded (Doc)';
                    } else {
                        $imageStatus = File::exists($fullLocalPath) ? 'Local Fallback' : 'Missing';
                    }
                } catch (\Throwable $e) {
                    $imageStatus = File::exists($fullLocalPath) ? 'Local Fallback' : 'Failed Download';
                    Log::warning("Could not download image for {$t['name']}: " . $e->getMessage());
                }
            } elseif (! File::exists($fullLocalPath)) {
                $imageStatus = 'Missing';
            }

            // Sync with MediaAsset library if table exists
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
                    $this->warn("  Warning syncing MediaAsset for {$t['name']}: " . $e->getMessage());
                }
            }

            // 1. Seed OurStoryTestimonial DB model
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

            // 2. Prepare item for Master Landing Page Settings
            $settingsItems[] = [
                'name' => $t['name'],
                'role' => $t['course'] . ' · ' . $t['university'],
                'quote' => $t['quote'] ?? strip_tags($t['testimonial']),
                'photo' => $imagePath,
                'photo_asset_id' => $mediaAssetId,
            ];

            $seededCount++;

            $tableRows[] = [
                $t['sort_order'],
                $t['name'],
                $t['course'],
                $t['university'],
                $t['country'],
                $imageStatus,
                $mediaAssetId ?? 'N/A',
            ];
        }

        $this->table(
            ['#', 'Name', 'Course', 'University', 'Country', 'Image', 'Asset ID'],
            $tableRows
        );

        // 3. Update Master Landing Page Settings
        try {
            $mbaSettings = app(MbaMastersTestimonialsSettings::class);
            $mbaSettings->label = 'Testimonials';
            $mbaSettings->heading = 'What Our Students Say';
            $mbaSettings->intro = 'Real experiences from working professionals and graduates across our global academic partner network.';
            $mbaSettings->show_section = true;
            $mbaSettings->items = $settingsItems;
            $mbaSettings->save();

            $this->info('✓ Master Landing Page Settings (MbaMastersTestimonialsSettings) updated with ' . count($settingsItems) . ' quotes.');
        } catch (\Throwable $e) {
            $this->warn('Warning updating MbaMastersTestimonialsSettings: ' . $e->getMessage());
        }

        // 4. Update Video Shorts on Master Landing Page if configured
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
                $this->info('✓ Master Landing Page Video Testimonials updated with ' . count($mergedVideos) . ' video shorts.');
            } catch (\Throwable $e) {
                // Non-critical
            }
        }

        // 5. Flush public cache
        try {
            PublicContentCache::flush(PublicContentCache::OUR_STORY);
            PublicContentCache::flush();
            $this->info('✓ PublicContentCache successfully flushed.');
        } catch (\Throwable $e) {
            // Ignore
        }

        $this->line('');
        $this->info("Successfully seeded {$seededCount} testimonials into DB and Admin Panel!");
        $this->line('  - Admin: About Section -> Our Story Page -> Testimonials tab (DB: our_story_testimonials)');
        $this->line('  - Admin: Landing Pages -> MBA Masters — Proof -> Testimonials section (Settings: mba_masters_testimonials)');
        $this->line('  - Public: /our-story & /online-mba-masters-uae');

        return self::SUCCESS;
    }
}
