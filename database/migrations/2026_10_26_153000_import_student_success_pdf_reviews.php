<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DATA_FILE = 'seeders/data/student_success_pdf_reviews.json';

    /**
     * Import the 24 verbatim reviews and their portraits from the supplied PDF.
     * Existing sample stories are kept in the database but hidden from the public page.
     */
    public function up(): void
    {
        if (! Schema::hasTable('student_success_stories') || ! Schema::hasTable('media_assets')) {
            throw new \RuntimeException('Student Success story and media tables must exist before importing PDF reviews.');
        }

        $dataPath = database_path(self::DATA_FILE);
        if (! File::exists($dataPath)) {
            throw new \RuntimeException("Student Success PDF review data file was not found: {$dataPath}");
        }

        $reviews = json_decode(File::get($dataPath), true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($reviews) || count($reviews) !== 24) {
            throw new \RuntimeException('Student Success PDF import must contain exactly 24 reviews.');
        }

        DB::transaction(function () use ($reviews): void {
            foreach ($reviews as $review) {
                $photoPath = ltrim((string) ($review['photo'] ?? ''), '/');
                $absolutePhotoPath = public_path($photoPath);

                if ($photoPath === '' || ! File::exists($absolutePhotoPath)) {
                    throw new \RuntimeException('Missing Student Success portrait: '.$absolutePhotoPath);
                }

                $hash = hash_file('sha256', $absolutePhotoPath);
                $imageInfo = @getimagesize($absolutePhotoPath);
                if (! is_string($hash) || ! is_array($imageInfo)) {
                    throw new \RuntimeException('Could not read Student Success portrait metadata: '.$absolutePhotoPath);
                }

                $diskEnvironment = (string) config('app.env', 'production');
                $mediaAsset = DB::table('media_assets')
                    ->where('hash', $hash)
                    ->where('disk_env', $diskEnvironment)
                    ->first();

                if ($mediaAsset) {
                    $mediaAssetId = (int) $mediaAsset->id;
                    DB::table('media_assets')
                        ->where('id', $mediaAssetId)
                        ->update([
                            'url' => '/'.$photoPath,
                            'folder' => 'student-success/stories',
                            'alt' => (string) ($review['name'] ?? ''),
                            'used' => true,
                            'is_duplicate' => false,
                            'deleted_at' => null,
                            'updated_at' => now(),
                        ]);
                } else {
                    $filename = basename($photoPath);
                    $publicId = 'student-success/stories/'.pathinfo($filename, PATHINFO_FILENAME);
                    if (DB::table('media_assets')->where('cloudinary_public_id', $publicId)->exists()) {
                        $publicId .= '-'.substr($hash, 0, 12);
                    }

                    $mediaAssetId = (int) DB::table('media_assets')->insertGetId([
                        'hash' => $hash,
                        'original_name' => $filename,
                        'mime_type' => 'image/jpeg',
                        'size_bytes' => File::size($absolutePhotoPath),
                        'width' => $imageInfo[0],
                        'height' => $imageInfo[1],
                        'cloudinary_public_id' => $publicId,
                        'url' => '/'.$photoPath,
                        'folder' => 'student-success/stories',
                        'alt' => (string) ($review['name'] ?? ''),
                        'disk_env' => $diskEnvironment,
                        'used' => true,
                        'is_duplicate' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $attributes = [
                    'name' => (string) ($review['name'] ?? ''),
                    'role' => (string) ($review['role'] ?? ''),
                    'quote' => (string) ($review['quote'] ?? ''),
                    'photo' => '/'.$photoPath,
                    'photo_asset_id' => $mediaAssetId,
                    'stars' => (int) ($review['stars'] ?? 0),
                    'sort_order' => (int) ($review['sort_order'] ?? 0),
                    'is_active' => (bool) ($review['is_active'] ?? true),
                    'deleted_at' => null,
                    'updated_at' => now(),
                ];

                $existingStory = DB::table('student_success_stories')
                    ->where('name', $attributes['name'])
                    ->where('quote', $attributes['quote'])
                    ->orderBy('id')
                    ->first();

                if ($existingStory) {
                    DB::table('student_success_stories')
                        ->where('id', $existingStory->id)
                        ->update($attributes);
                } else {
                    $attributes['created_at'] = now();
                    DB::table('student_success_stories')->insert($attributes);
                }
            }

            $this->setStarterStoriesActive(false);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('student_success_stories')) {
            return;
        }

        $dataPath = database_path(self::DATA_FILE);
        if (File::exists($dataPath)) {
            $reviews = json_decode(File::get($dataPath), true, 512, JSON_THROW_ON_ERROR);

            DB::transaction(function () use ($reviews): void {
                foreach ($reviews as $review) {
                    $story = DB::table('student_success_stories')
                        ->where('name', (string) ($review['name'] ?? ''))
                        ->where('quote', (string) ($review['quote'] ?? ''))
                        ->orderBy('id')
                        ->first();

                    if ($story) {
                        DB::table('student_success_stories')
                            ->where('id', $story->id)
                            ->delete();
                    }
                }

                $this->setStarterStoriesActive(true);
            });
        }
    }

    private function setStarterStoriesActive(bool $active): void
    {
        $starterStoryNames = [
            'Rahul S.',
            'Fatima A.',
            'Mohammed K.',
            'Priya N.',
            'Daniel O.',
            'Sara E.',
        ];

        foreach ($starterStoryNames as $name) {
            DB::table('student_success_stories')
                ->where('name', $name)
                ->update([
                    'is_active' => $active,
                    'updated_at' => now(),
                ]);
        }
    }
};
