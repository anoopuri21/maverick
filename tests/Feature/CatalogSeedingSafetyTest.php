<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Database\Seeders\BachelorsProgramsSeeder;
use Database\Seeders\ExistingCatalogProgramsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeedingSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_bachelors_only_uses_existing_references_and_preserves_admin_toggles_on_reseed(): void
    {
        [$category, $partner] = $this->bachelorReferences();

        $this->seed(BachelorsProgramsSeeder::class);

        $this->assertDatabaseCount('program_categories', 1);
        $this->assertDatabaseCount('university_partners', 1);
        $this->assertDatabaseCount('programs', 5);
        $this->assertSame(5, Program::query()->where('is_active', false)->count());
        $this->assertSame(5, \App\Filament\Resources\ProgramResource::getEloquentQuery()->count());
        $this->assertSame(0, Program::query()->where('is_featured', true)->count());
        $this->assertSame(5, Program::query()
            ->where('program_category_id', $category->id)
            ->where('university_partner_id', $partner->id)->count());

        $data = require database_path('seeders/data/bachelors_programs.php');
        $slugs = array_column($data['programs'], 'slug');
        $this->assertEqualsCanonicalizing($slugs, Program::query()->pluck('slug')->all());
        $this->assertSame(6, Program::query()->where('slug', $slugs[0])->firstOrFail()->faqs()->count());

        $edited = Program::query()->where('slug', $slugs[0])->firstOrFail();
        $edited->update(['is_active' => true, 'is_featured' => true]);
        $this->seed(BachelorsProgramsSeeder::class);

        $this->assertDatabaseCount('programs', 5);
        $this->assertDatabaseCount('program_categories', 1);
        $this->assertDatabaseCount('university_partners', 1);
        $this->assertTrue($edited->refresh()->is_active);
        $this->assertTrue($edited->is_featured);
        $this->assertSame(6, $edited->faqs()->count());
    }

    public function test_bachelors_only_refuses_a_missing_partner_without_creating_anything(): void
    {
        ProgramCategory::query()->create(['name' => 'Bachelors', 'slug' => 'bachelors']);

        $this->assertSeedFails(BachelorsProgramsSeeder::class, 'Existing university partner required: girne-american-university');

        $this->assertDatabaseCount('programs', 0);
        $this->assertDatabaseCount('program_categories', 1);
        $this->assertDatabaseCount('university_partners', 0);
    }

    public function test_all_existing_only_seeds_67_without_adding_categories_or_partners(): void
    {
        $this->bachelorReferences();
        foreach (['doctorate', 'diploma'] as $slug) {
            ProgramCategory::query()->create(['name' => ucfirst($slug), 'slug' => $slug]);
        }
        foreach (['rushford-business-school', 'gatehouse-awards', 'qualifi'] as $slug) {
            UniversityPartner::query()->create(['name' => $slug, 'slug' => $slug, 'country' => 'Example']);
        }

        $this->seed(ExistingCatalogProgramsSeeder::class);

        $this->assertDatabaseCount('programs', 67);
        $this->assertDatabaseCount('program_categories', 3);
        $this->assertDatabaseCount('university_partners', 4);
        $this->assertSame(67, Program::query()->where('is_active', false)->count());
        $this->assertSame(13, Program::query()->whereHas('programCategory', fn ($q) => $q->where('slug', 'doctorate'))->count());
        $this->assertSame(49, Program::query()->whereHas('programCategory', fn ($q) => $q->where('slug', 'diploma'))->count());

        $this->seed(ExistingCatalogProgramsSeeder::class);
        $this->assertDatabaseCount('programs', 67);
        $this->assertDatabaseCount('program_categories', 3);
        $this->assertDatabaseCount('university_partners', 4);
    }

    public function test_all_existing_only_stops_before_inserting_if_a_category_is_missing(): void
    {
        $this->bachelorReferences();

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class, 'Existing programme category required: doctorate');

        $this->assertDatabaseCount('programs', 0);
        $this->assertDatabaseCount('program_categories', 1);
        $this->assertDatabaseCount('university_partners', 1);
    }

    public function test_all_existing_only_stops_before_inserting_if_an_awarding_partner_is_missing(): void
    {
        $this->bachelorReferences();
        foreach (['doctorate', 'diploma'] as $slug) {
            ProgramCategory::query()->create(['name' => ucfirst($slug), 'slug' => $slug]);
        }
        foreach (['rushford-business-school', 'gatehouse-awards'] as $slug) {
            UniversityPartner::query()->create(['name' => $slug, 'slug' => $slug, 'country' => 'Example']);
        }

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class, 'Existing university partner required: qualifi');

        $this->assertDatabaseCount('programs', 0);
        $this->assertDatabaseCount('program_categories', 3);
        $this->assertDatabaseCount('university_partners', 3);
    }

    public function test_late_slug_collision_rolls_back_earlier_rows_without_reassignment(): void
    {
        [, $partner] = $this->bachelorReferences();
        $wrong = ProgramCategory::query()->create(['name' => 'Another', 'slug' => 'another']);
        $data = require database_path('seeders/data/bachelors_programs.php');
        // The first two would be written before this collision if the run
        // were not atomic. Neither should survive the rollback.
        $slug = $data['programs'][2]['slug'];
        $program = Program::query()->create([
            'title' => 'Existing unrelated programme',
            'slug' => $slug,
            'program_category_id' => $wrong->id,
            'university_partner_id' => $partner->id,
        ]);

        $this->assertSeedFails(BachelorsProgramsSeeder::class, "Refusing to reassign existing programme: {$slug}");

        $this->assertDatabaseCount('programs', 1);
        $this->assertSame($wrong->id, $program->refresh()->program_category_id);
        $this->assertSame('Existing unrelated programme', $program->title);
    }

    private function bachelorReferences(): array
    {
        return [
            ProgramCategory::query()->create(['name' => 'Bachelors', 'slug' => 'bachelors']),
            UniversityPartner::query()->create([
                'name' => 'Girne American University',
                'slug' => 'girne-american-university',
                'country' => 'Northern Cyprus',
            ]),
        ];
    }

    private function assertSeedFails(string $class, string $message): void
    {
        try {
            $this->seed($class);
            $this->fail("Expected {$class} to abort");
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString($message, $exception->getMessage());
        }
    }
}
