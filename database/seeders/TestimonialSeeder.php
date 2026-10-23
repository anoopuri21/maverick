<?php

namespace Database\Seeders;

use App\Models\OurStoryTestimonial;
use App\Services\MediaLibraryService;
use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public const MEDIA_FOLDER = 'testimonials';

    public const SOURCE_DIR = 'assets/images/testimonials';

    public function run(): void
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = require database_path('seeders/data/testimonials.php');
        $library = app(MediaLibraryService::class);
        $defaultPages = [
            TestimonialPages::MBA_MASTERS_LANDING,
            TestimonialPages::OUR_STORY,
        ];

        OurStoryTestimonial::withTrashed()->forceDelete();

        foreach ($items as $index => $item) {
            $asset = $library->importLocalFile(
                public_path(self::SOURCE_DIR . '/' . $item['file']),
                self::MEDIA_FOLDER,
                $item['name'],
            );

            OurStoryTestimonial::create([
                'name' => $item['name'],
                'organisation' => $item['organisation'],
                'position' => $item['position'],
                'country' => $item['country'],
                'rating' => 5,
                'testimonial' => '<p>' . e($item['quote']) . '</p>',
                'photo' => $asset->url,
                'media_asset_id' => $asset->id,
                'pages' => $item['pages'] ?? $defaultPages,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        PublicContentCache::flush();
        $this->command?->info('Seeded ' . count($items) . ' student testimonials across registered pages.');
    }
}
