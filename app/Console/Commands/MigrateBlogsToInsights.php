<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateBlogsToInsights extends Command
{
    protected $signature = 'insights:migrate-blogs';
    protected $description = 'Copy all blog_posts records into the new insights table with category=blogs (non-destructive)';

    public function handle()
    {
        $count = \App\Models\BlogPost::count();
        $this->info("Found {$count} blog posts to migrate.");

        $bar = $this->output->createProgressBar($count);
        $migrated = 0;
        $skipped = 0;

        \App\Models\BlogPost::chunk(50, function ($posts) use (&$migrated, &$skipped, $bar) {
            foreach ($posts as $post) {
                $bar->advance();

                // Idempotent: if this post was already migrated
                // (matched by legacy_id, or by slug if legacy_id is
                // null), skip it — this makes the command safely
                // re-runnable without creating duplicates.
                $exists = \App\Models\Insight::where('legacy_id', $post->legacy_id)
                    ->orWhere('slug', $post->slug)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                \App\Models\Insight::create([
                    'legacy_id'             => $post->legacy_id,
                    'title'                 => $post->title,
                    'slug'                  => $post->slug,
                    'excerpt'               => $post->excerpt,
                    'content'               => $post->content,
                    'featured_image_url'    => $post->featured_image_url,
                    'featured_image_alt'    => $post->featured_image_alt,
                    'categories'            => ['blogs'],
                    'tags'                  => $post->tags ?? [],
                    'author_name'           => $post->author_name,
                    'author_avatar_url'     => $post->author_avatar_url,
                    'author_bio'            => $post->author_bio,
                    'published_at'          => $post->published_at,
                    'reading_time_minutes'  => $post->reading_time_minutes,
                    'is_featured'           => $post->is_featured,
                    'meta_title'            => $post->meta_title,
                    'meta_description'      => $post->meta_description,
                ]);

                $migrated++;
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Migrated: {$migrated} | Skipped (already existed): {$skipped}");
    }
}
