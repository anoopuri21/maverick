<?php

namespace Tests\Feature;

use App\Models\StudentSuccessStory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StudentSuccessPdfReviewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_reviews_are_imported_verbatim_with_their_portraits_and_media_assets(): void
    {
        $reviews = json_decode(
            File::get(database_path('seeders/data/student_success_pdf_reviews.json')),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        $this->assertCount(24, $reviews);

        $stories = StudentSuccessStory::query()
            ->whereIn('name', array_column($reviews, 'name'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $this->assertCount(24, $stories);

        foreach ($reviews as $index => $review) {
            $story = $stories->get($index);
            $this->assertNotNull($story);
            $this->assertSame($review['name'], $story->name);
            $this->assertSame($review['role'], $story->role);
            $this->assertSame($review['quote'], $story->quote);
            $this->assertSame($review['photo'], $story->photo);
            $this->assertSame($review['sort_order'], $story->sort_order);
            $this->assertSame(0, $story->stars);
            $this->assertTrue($story->is_active);
            $this->assertFileExists(public_path(ltrim($review['photo'], '/')));
            $this->assertSame($review['photo'], $story->getMediaUrl('photo'));

            $mediaAsset = $story->mediaAsset('photo');
            $this->assertNotNull($mediaAsset);
            $this->assertSame($review['name'], $mediaAsset->alt);
            $this->assertTrue($mediaAsset->used);
        }

        $this->assertSame(0, StudentSuccessStory::query()
            ->whereIn('name', ['Rahul S.', 'Fatima A.', 'Mohammed K.', 'Priya N.', 'Daniel O.', 'Sara E.'])
            ->where('is_active', true)
            ->count());
    }
}
