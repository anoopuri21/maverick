<?php

namespace Tests\Feature;

use App\Filament\Resources\UniversityPartnerResource\Pages\CreateUniversityPartner;
use App\Models\MediaAsset;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
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
            'description' => 'An example MBA programme overview.',
            'highlights' => [
                ['label' => 'Duration', 'value' => '1 Year'],
            ],
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

    public function test_active_university_requires_a_name_and_logo_in_admin(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($user)
            ->test(CreateUniversityPartner::class)
            ->fillForm([
                'name' => '',
                'logo_url' => null,
                'logo_url_asset_id' => null,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'logo_url_asset_id' => 'required',
            ]);
    }

    public function test_last_active_partner_wall_entry_cannot_be_deactivated(): void
    {
        $partner = UniversityPartner::query()->create([
            'name' => 'Only Active University',
            'logo_url' => 'https://cdn.example.com/only-active-university.png',
            'is_active' => true,
        ]);

        try {
            $partner->update(['is_active' => false]);
            $this->fail('The last active university partner should not be deactivated.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('is_active', $exception->errors());
        }

        $this->assertTrue($partner->fresh()->is_active);
    }

    public function test_last_active_partner_wall_entry_cannot_be_deleted(): void
    {
        $partner = UniversityPartner::query()->create([
            'name' => 'Only Active University',
            'logo_url' => 'https://cdn.example.com/only-active-university.png',
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        $partner->delete();
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
