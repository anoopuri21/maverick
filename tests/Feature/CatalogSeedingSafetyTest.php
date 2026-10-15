<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use App\Support\PublicContentCache;
use Database\Seeders\BachelorsProgramsSeeder;
use Database\Seeders\ExistingCatalogProgramsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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

    public function test_existing_only_refuses_changed_catalogue_identity_and_rolls_back(): void
    {
        $this->fullCatalogReferences();
        $program = Program::query()->create([
            'title' => 'Different qualification — do not overwrite',
            'slug' => 'bsc-economics-girne-american-university',
            'level' => 'BSc',
            'program_category_id' => ProgramCategory::query()->where('slug', 'bachelors')->firstOrFail()->id,
            'university_partner_id' => UniversityPartner::query()->where('slug', 'girne-american-university')->firstOrFail()->id,
        ]);

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class, 'Refusing to change existing programme identity: bsc-economics-girne-american-university');
        $this->assertDatabaseCount('programs', 1);
        $this->assertSame('Different qualification — do not overwrite', $program->refresh()->title);
    }

    public function test_reseed_updates_four_year_duration_and_question_without_erasing_admin_settings(): void
    {
        $this->bachelorReferences();
        $this->seed(BachelorsProgramsSeeder::class);

        $program = Program::query()->where('slug', 'bsc-economics-girne-american-university')->firstOrFail();
        $faq = $program->faqs()->where('sort_order', 1)->firstOrFail();
        $oldId = $faq->id;
        $faq->update(['question' => 'Old question about Four years?', 'answer' => 'Old answer', 'is_active' => false]);
        $program->seo()->firstOrFail()->update(['canonical_url' => 'https://example.test/keep-this']);
        $program->update([
            'duration' => 'Four years',
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 999,
            'testimonials' => [['title' => 'Do not erase']],
            'reviews' => [['title' => 'Do not erase']],
        ]);

        $this->seed(BachelorsProgramsSeeder::class);

        $this->assertSame('4 years', $program->refresh()->duration);
        $this->assertTrue($program->is_active);
        $this->assertTrue($program->is_featured);
        $this->assertSame(999, $program->sort_order);
        $this->assertSame([['title' => 'Do not erase']], $program->testimonials);
        $this->assertSame([['title' => 'Do not erase']], $program->reviews);
        $this->assertSame('https://example.test/keep-this', $program->seo->canonical_url);
        $this->assertSame(6, $program->faqs()->count());
        $this->assertSame($oldId, $faq->refresh()->id);
        $this->assertFalse($faq->is_active);
        $this->assertStringContainsString('4-year', $faq->question);
        $this->assertSame(1, $program->faqs()->where('sort_order', 1)->count());
        $this->assertSame(0, $program->faqs()->where('question', 'Old question about Four years?')->count());
    }

    public function test_ambiguous_faq_slot_aborts_without_partial_copy_updates(): void
    {
        $this->bachelorReferences();
        $this->seed(BachelorsProgramsSeeder::class);

        $program = Program::query()->where('slug', 'bsc-economics-girne-american-university')->firstOrFail();
        $program->faqs()->create([
            'question' => 'An admin FAQ at the same slot',
            'answer' => '<p>Keep for review.</p>',
            'sort_order' => 1,
            'is_active' => false,
        ]);
        $program->update(['duration' => 'Four years']);

        $this->assertSeedFails(BachelorsProgramsSeeder::class, 'Ambiguous FAQ slot 1 for programme: bsc-economics-girne-american-university');
        $this->assertSame('Four years', $program->refresh()->duration);
        $this->assertSame(7, $program->faqs()->count());
    }

    public function test_one_existing_command_refreshes_catalogue_and_present_legacy_without_reactivating_them(): void
    {
        $this->fullCatalogReferences();
        $this->seed(ExistingCatalogProgramsSeeder::class);

        $bsc = Program::query()->where('slug', 'bsc-economics-girne-american-university')->firstOrFail();
        $bsc->update(['duration' => 'Four years']);

        $mbaCategory = ProgramCategory::query()->create(['name' => 'MBA', 'slug' => 'mba']);
        $gau = UniversityPartner::query()->where('slug', 'girne-american-university')->firstOrFail();
        $rushford = UniversityPartner::query()->where('slug', 'rushford-business-school')->firstOrFail();
        $master = Program::query()->create([
            'title' => 'MBA in Business Management',
            'slug' => 'mba-in-business-management-girne-american-university',
            'program_category_id' => $mbaCategory->id,
            'university_partner_id' => $gau->id,
            'level' => 'MBA',
            'duration' => 'Twelve months',
            'is_active' => false,
            'is_featured' => true,
            'sort_order' => 998,
            'testimonials' => [['title' => 'Retained testimonial']],
        ]);
        $master->seo()->create(['meta_title' => 'Original', 'canonical_url' => 'https://example.test/mba']);
        $bba = Program::query()->create([
            'title' => 'BBA in Business Administration',
            'slug' => 'bba-business-administration-rushford-business-school',
            'program_category_id' => ProgramCategory::query()->where('slug', 'bachelors')->firstOrFail()->id,
            'university_partner_id' => $rushford->id,
            'level' => 'BBA',
            'duration' => 'Twenty to twenty-four months',
            'is_active' => false,
            'is_featured' => true,
            'sort_order' => 997,
            'reviews' => [['title' => 'Retained review']],
        ]);
        $uca = UniversityPartner::query()->create(['name' => 'UCA', 'slug' => 'university-for-the-creative-arts', 'country' => 'UK']);
        $uws = UniversityPartner::query()->create(['name' => 'UWS', 'slug' => 'university-of-the-west-of-scotland', 'country' => 'UK']);
        foreach (['global-mba' => $uca, 'mba-in-international-business-university-of-the-west-of-scotland' => $uws] as $slug => $partner) {
            $exception = Program::query()->create([
                'title' => 'Leave this programme alone',
                'slug' => $slug,
                'program_category_id' => $mbaCategory->id,
                'university_partner_id' => $partner->id,
                'duration' => 'Four years',
                'short_description' => 'Do not rewrite this row',
                'is_active' => true,
            ]);
            $exception->seo()->create(['meta_title' => 'Leave SEO alone']);
            $exception->faqs()->create(['question' => 'Leave this FAQ alone?', 'answer' => 'Unchanged.', 'sort_order' => 1]);
        }

        Cache::put(PublicContentCache::PROGRAMS_LISTING, 'stale listing', PublicContentCache::TTL);
        $this->seed(ExistingCatalogProgramsSeeder::class);
        $this->assertFalse(Cache::has(PublicContentCache::PROGRAMS_LISTING));
        $this->seed(ExistingCatalogProgramsSeeder::class); // Repeat with legacy rows present: no duplicates.

        $this->assertSame('4 years', $bsc->refresh()->duration);
        $this->assertSame('12 to 15 months', $master->refresh()->duration);
        $this->assertFalse($master->is_active);
        $this->assertTrue($master->is_featured);
        $this->assertSame(998, $master->sort_order);
        $this->assertSame([['title' => 'Retained testimonial']], $master->testimonials);
        $this->assertStringContainsString('12 to 15 months', $master->description);
        $this->assertSame('https://example.test/mba', $master->seo->canonical_url);
        $this->assertSame('20 to 24 months', $bba->refresh()->duration);
        $this->assertFalse($bba->is_active);
        $this->assertTrue($bba->is_featured);
        $this->assertSame(997, $bba->sort_order);
        $this->assertSame([['title' => 'Retained review']], $bba->reviews);
        foreach (['global-mba', 'mba-in-international-business-university-of-the-west-of-scotland'] as $slug) {
            $exception = Program::query()->where('slug', $slug)->firstOrFail();
            $this->assertSame('Leave this programme alone', $exception->title);
            $this->assertSame('Four years', $exception->duration);
            $this->assertSame('Do not rewrite this row', $exception->short_description);
            $this->assertTrue($exception->is_active);
            $this->assertSame('Leave SEO alone', $exception->seo()->firstOrFail()->meta_title);
            $this->assertSame('Leave this FAQ alone?', $exception->faqs()->firstOrFail()->question);
        }
        $this->assertDatabaseCount('programs', 71); // 67 catalogue + 2 legacy updates + 2 untouched exceptions
    }

    public function test_wrong_legacy_partner_rolls_back_the_catalogue_updates_too(): void
    {
        $this->fullCatalogReferences();
        $wrong = ProgramCategory::query()->create(['name' => 'Wrong', 'slug' => 'wrong']);
        $gau = UniversityPartner::query()->where('slug', 'girne-american-university')->firstOrFail();
        Program::query()->create([
            'title' => 'MBA in Business Management',
            'slug' => 'mba-in-business-management-girne-american-university',
            'program_category_id' => $wrong->id,
            'university_partner_id' => $gau->id,
        ]);

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class, 'Refusing to reassign existing legacy programme: mba-in-business-management-girne-american-university');
        $this->assertDatabaseCount('programs', 1);
        $this->assertDatabaseCount('program_categories', 4);
        $this->assertDatabaseCount('university_partners', 4);
    }

    private function fullCatalogReferences(): void
    {
        $this->bachelorReferences();
        foreach (['doctorate', 'diploma'] as $slug) {
            ProgramCategory::query()->create(['name' => ucfirst($slug), 'slug' => $slug]);
        }
        foreach (['rushford-business-school', 'gatehouse-awards', 'qualifi'] as $slug) {
            UniversityPartner::query()->create(['name' => $slug, 'slug' => $slug, 'country' => 'Example']);
        }
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
