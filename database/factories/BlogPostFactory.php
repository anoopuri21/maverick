<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);

        return [
            'legacy_id' => null,
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph(),
            'content' => '<h2>' . $this->faker->sentence() . '</h2><p>' . $this->faker->paragraphs(3, true) . '</p>',
            'featured_image_url' => null,
            'featured_image_alt' => null,
            'category' => 'Blogs',
            'tags' => [],
            'author_name' => 'Maverick Business Academy',
            'author_avatar_url' => null,
            'author_bio' => null,
            'published_at' => now(),
            'reading_time_minutes' => $this->faker->numberBetween(2, 10),
            'is_featured' => false,
            'meta_title' => $title,
            'meta_description' => $this->faker->sentence(),
        ];
    }
}
