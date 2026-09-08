<?php

namespace Tests\Feature;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\ProgramResource\Pages\CreateProgram;
use App\Filament\Resources\ProgramResource\Pages\EditProgram;
use App\Models\MediaAsset;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProgramHeroImageUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_field_from_asset_keeps_typed_url_when_asset_id_empty(): void
    {
        $result = MediaPicker::syncFieldFromAsset([
            'image_url' => 'https://cdn.example.com/manual-hero.jpg',
            'image_url_asset_id' => null,
        ], 'image_url');

        $this->assertSame('https://cdn.example.com/manual-hero.jpg', $result['image_url']);
        $this->assertNull($result['image_url_asset_id']);
    }

    public function test_sync_field_from_asset_overwrites_url_from_media_asset(): void
    {
        $asset = MediaAsset::query()->create([
            'hash' => str_repeat('a', 64),
            'original_name' => 'hero.jpg',
            'mime_type' => 'image/jpeg',
            'cloudinary_public_id' => 'programs/hero',
            'url' => 'https://cdn.example.com/library-hero.jpg',
            'folder' => 'programs',
            'disk_env' => 'testing',
        ]);

        $result = MediaPicker::syncFieldFromAsset([
            'image_url' => 'https://cdn.example.com/stale.jpg',
            'image_url_asset_id' => $asset->id,
        ], 'image_url');

        $this->assertSame('https://cdn.example.com/library-hero.jpg', $result['image_url']);
        $this->assertSame($asset->id, $result['image_url_asset_id']);
    }

    public function test_sync_field_from_asset_nulls_url_when_both_empty(): void
    {
        $result = MediaPicker::syncFieldFromAsset([
            'image_url' => '',
            'image_url_asset_id' => null,
        ], 'image_url');

        $this->assertNull($result['image_url']);
        $this->assertNull($result['image_url_asset_id']);
    }

    public function test_create_program_persists_hero_image_url_without_asset_id(): void
    {
        [$user, $category, $partner] = $this->seedAdminContext();

        Livewire::actingAs($user)
            ->test(CreateProgram::class)
            ->fillForm([
                'program_category_id' => $category->id,
                'title' => 'BBA Hero URL Persist',
                'slug' => 'bba-hero-url-persist',
                'university_partner_id' => $partner->id,
                'level' => 'Undergraduate',
                'image_url' => 'https://cdn.example.com/manual-hero.jpg',
                'image_url_asset_id' => null,
            ])
            ->call('create')
            ->assertHasNoErrors()
            ->assertNotified();

        $program = Program::query()->where('slug', 'bba-hero-url-persist')->first();

        $this->assertNotNull($program);
        $this->assertSame('https://cdn.example.com/manual-hero.jpg', $program->image_url);
        $this->assertNull($program->image_url_asset_id);
    }

    public function test_create_program_uses_media_asset_url_when_asset_selected(): void
    {
        [$user, $category, $partner] = $this->seedAdminContext();

        $asset = MediaAsset::query()->create([
            'hash' => str_repeat('b', 64),
            'original_name' => 'library-hero.jpg',
            'mime_type' => 'image/jpeg',
            'cloudinary_public_id' => 'programs/library-hero',
            'url' => 'https://cdn.example.com/library-hero.jpg',
            'folder' => 'programs',
            'disk_env' => 'testing',
        ]);

        Livewire::actingAs($user)
            ->test(CreateProgram::class)
            ->fillForm([
                'program_category_id' => $category->id,
                'title' => 'BBA Library Hero',
                'slug' => 'bba-library-hero',
                'university_partner_id' => $partner->id,
                'level' => 'Undergraduate',
                'image_url' => 'https://cdn.example.com/stale.jpg',
                'image_url_asset_id' => $asset->id,
            ])
            ->call('create')
            ->assertHasNoErrors()
            ->assertNotified();

        $program = Program::query()->where('slug', 'bba-library-hero')->first();

        $this->assertNotNull($program);
        $this->assertSame('https://cdn.example.com/library-hero.jpg', $program->image_url);
        $this->assertSame($asset->id, $program->image_url_asset_id);
    }

    public function test_edit_program_persists_hero_image_url_without_asset_id(): void
    {
        [$user, $category, $partner] = $this->seedAdminContext();

        $program = Program::query()->create([
            'program_category_id' => $category->id,
            'university_partner_id' => $partner->id,
            'title' => 'Existing Program',
            'slug' => 'existing-program-hero',
            'level' => 'Undergraduate',
            'is_active' => true,
            'image_url' => null,
            'image_url_asset_id' => null,
        ]);

        Livewire::actingAs($user)
            ->test(EditProgram::class, ['record' => $program->getRouteKey()])
            ->fillForm([
                'image_url' => 'https://cdn.example.com/edited-hero.jpg',
                'image_url_asset_id' => null,
            ])
            ->call('save')
            ->assertHasNoErrors()
            ->assertNotified();

        $program->refresh();

        $this->assertSame('https://cdn.example.com/edited-hero.jpg', $program->image_url);
        $this->assertNull($program->image_url_asset_id);
    }

    /**
     * @return array{0: User, 1: ProgramCategory, 2: UniversityPartner}
     */
    private function seedAdminContext(): array
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = ProgramCategory::query()->create([
            'name' => 'Undergraduate',
            'slug' => 'undergraduate',
        ]);
        $partner = UniversityPartner::query()->create([
            'name' => 'Example University',
            'country' => 'United Kingdom',
            'is_active' => true,
        ]);

        return [$user, $category, $partner];
    }
}
