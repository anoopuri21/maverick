<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ImportLegacyBlogs extends Command
{
    protected $signature = 'blogs:import {file=storage/app/imports/blogs.json}';
    protected $description = 'Import legacy blog posts from JSON into blog_posts table';

    public function handle(): int
    {
        $path = base_path($this->argument('file'));

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON: ' . json_last_error_msg());
            return self::FAILURE;
        }

        $this->info('Found ' . count($data) . ' records in file.');

        $bar = $this->output->createProgressBar(count($data));
        $imported = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($data as $row) {
            $bar->advance();

            if (($row['status'] ?? '') !== 'publish') {
                $skipped++;
                continue;
            }

            try {
                $this->importRow($row);
                $imported++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->warn("Failed (legacy_id: " . ($row['_id'] ?? 'unknown') . "): {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Imported: {$imported} | Skipped (non-published): {$skipped} | Failed: {$failed}");

        return self::SUCCESS;
    }

    protected function importRow(array $row): void
    {
        $legacyId = $row['_id'];

        $slug = $this->resolveUniqueSlug($row['slug'] ?? Str::slug($row['title']), $legacyId);

        $cleanContent = $this->cleanContent($row['content'] ?? '');
        $excerpt = $this->generateExcerpt($row['excerpt'] ?? '', $cleanContent);

        $imageUrl = $row['image']['url'] ?? null;
        $imageAlt = $row['image']['alt'] ?? null;

        $metaTitle = $row['seo']['metaTitle'] ?? '';
        $metaDescription = $row['seo']['metaDescription'] ?? '';

        $readingTime = $row['meta']['readingTime'] ?? null;

        BlogPost::updateOrCreate(
            ['legacy_id' => $legacyId],
            [
                'title'                => $row['title'],
                'slug'                 => $slug,
                'excerpt'              => $excerpt,
                'content'              => $cleanContent,
                'featured_image_url'   => $imageUrl ?: null,
                'featured_image_alt'   => $imageAlt ?: $row['title'],
                'category'             => $row['categories'][0] ?? 'Blogs',
                'tags'                 => [],
                'author_name'          => $row['author'] ?: 'Maverick Business Academy',
                'published_at'         => $this->resolveDate($row['publishedDate'] ?? null),
                'reading_time_minutes' => $readingTime && $readingTime > 0
                    ? (int) $readingTime
                    : $this->calculateReadingTime($cleanContent),
                'is_featured'          => false,
                'meta_title'           => $metaTitle ?: $row['title'],
                'meta_description'     => $metaDescription ?: $excerpt,
            ]
        );
    }

    protected function cleanContent(string $html): string
    {
        $html = preg_replace(
            '/<span style="font-weight:\s*400;?">(.*?)<\/span>/is',
            '$1',
            $html
        );

        return trim($html);
    }

    protected function generateExcerpt(?string $existingExcerpt, string $content): string
    {
        if (!empty(trim($existingExcerpt ?? ''))) {
            return Str::limit(strip_tags($existingExcerpt), 160);
        }

        $plainText = html_entity_decode(strip_tags($content));
        $plainText = preg_replace('/\s+/', ' ', $plainText);

        return Str::limit(trim($plainText), 160);
    }

    protected function resolveDate(?string $date): Carbon
    {
        try {
            return $date ? Carbon::parse($date) : now();
        } catch (\Throwable $e) {
            return now();
        }
    }

    protected function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, (int) ceil($wordCount / 200));
    }

    protected function resolveUniqueSlug(string $slug, $legacyId): string
    {
        $slug = Str::slug($slug);
        $original = $slug;
        $count = 1;

        while (
            BlogPost::where('slug', $slug)
                ->where('legacy_id', '!=', $legacyId)
                ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
