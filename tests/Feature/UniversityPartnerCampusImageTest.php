<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniversityPartnerCampusImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_program_detail_uses_the_partner_campus_image_in_both_university_sections(): void
    {
        $campusImage = MediaAsset::query()->create([
            'hash' => str_repeat('c', 64),
            'original_name' => 'university-campus.jpg',
            'mime_type' => 'image/jpeg',
            'cloudinary_public_id' => 'university-partners/campuses/example',
            'url' => 'https://cdn.example.com/university-campus.jpg',
            'folder' => 'university-partners/campuses',
            'disk_env' => 'testing',
        ]);

        $partner = UniversityPartner::query()->create([
            'name' => 'Example University',
            'country' => 'United Kingdom',
            'logo_url' => 'https://cdn.example.com/university-logo.png',
            'campus_image_url' => 'https://cdn.example.com/stale-campus.jpg',
            'campus_image_url_asset_id' => $campusImage->id,
            'description' => 'An example university.',
            'is_active' => true,
        ]);

        $category = ProgramCategory::query()->create([
            'name' => 'MBA',
            'slug' => 'mba',
            'is_active' => true,
        ]);

        Program::query()->create([
            'program_category_id' => $category->id,
            'university_partner_id' => $partner->id,
            'title' => 'Example MBA',
            'slug' => 'example-mba',
            'is_active' => true,
        ]);

        $response = $this->get('/programs/example-mba');

        $response->assertOk();
        $this->assertSame(
            2,
            substr_count($response->getContent(), 'src="https://cdn.example.com/university-campus.jpg"')
        );
        $response->assertDontSee('src="https://cdn.example.com/university-logo.png"', false);
        $response->assertDontSee('stale-campus.jpg', false);
    }

    public function test_program_detail_keeps_the_existing_logo_fallback_without_a_campus_image(): void
    {
        $partner = new UniversityPartner([
            'name' => 'Legacy University',
            'logo_url' => 'https://cdn.example.com/legacy-logo.png',
        ]);
        $program = new Program;
        $program->setRelation('universityPartner', $partner);

        $this->assertSame(
            'https://cdn.example.com/legacy-logo.png',
            $program->university_object->image
        );
    }
}
