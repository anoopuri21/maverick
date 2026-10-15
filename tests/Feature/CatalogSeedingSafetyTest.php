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
        $this->assertEqualsCanonicalizing([
            'bsc-tourism-and-hospitality-management-girne-american-university',
            'bsc-international-business-management-girne-american-university',
            'bsc-marketing-girne-american-university',
            'bsc-economics-girne-american-university',
            'bsc-management-information-systems-girne-american-university',
        ], $slugs); // 72, 74 and 78 are separately seeded as incomplete drafts; 75 is withheld.
        foreach ($data['programs'] as $row) {
            $this->assertFalse($row['is_active']);
        }
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

    public function test_all_existing_only_seeds_67_pages_and_3_incomplete_drafts_without_new_references(): void
    {
        $this->fullCatalogReferences();

        $this->seed(ExistingCatalogProgramsSeeder::class);

        $this->assertDatabaseCount('programs', 70);
        $this->assertDatabaseCount('program_categories', 3);
        $this->assertDatabaseCount('university_partners', 5);
        $this->assertSame(70, Program::query()->where('is_active', false)->count());
        $this->assertSame(70, \App\Filament\Resources\ProgramResource::getEloquentQuery()->count());
        $this->assertSame(13, Program::query()->whereHas('programCategory', fn ($q) => $q->where('slug', 'doctorate'))->count());
        $this->assertSame(49, Program::query()->whereHas('programCategory', fn ($q) => $q->where('slug', 'diploma'))->count());
        $this->assertSame(8, Program::query()->whereHas('programCategory', fn ($q) => $q->where('slug', 'bachelors'))->count());
        $this->assertSame(0, Program::query()->where('slug', 'bsc-accounting-girne-american-university')->count());

        $drafts = require database_path('seeders/data/bachelors_admin_drafts.php');
        $this->assertSame([72, 74, 78], array_column($drafts['programs'], 'tracker_sno'));
        $this->assertSame([
            'Accounting and Finance Management', 'Human Resources Management', 'BA Global Business (Level 9)',
        ], array_column($drafts['programs'], 'title'));
        foreach ($drafts['programs'] as $row) {
            $this->assertTrue($row['admin_draft']);
            $this->assertFalse($row['is_active']);
            $this->assertSame('', $row['meta_title']);
            $this->assertSame('', $row['meta_description']);
            $this->assertSame([], $row['fees']);
            $this->assertSame([], $row['learning']);
            $this->assertSame([], $row['careers']);
            $this->assertSame([], $row['faqs']);
            $program = Program::query()->where('slug', $row['slug'])->firstOrFail();
            $this->assertFalse($program->is_active);
            $this->assertFalse($program->is_featured);
            $this->assertSame($row['university_slug'], $program->universityPartner->slug);
            $this->assertSame('', $program->seo()->firstOrFail()->meta_title);
            $this->get(route('programs.show', ['slug' => $row['slug']]))->assertNotFound();
        }
        $this->assertSame('', Program::query()->where('slug', $drafts['programs'][2]['slug'])->firstOrFail()->duration);
        $this->assertSame([], $drafts['programs'][2]['structure']); // No other UWS award's syllabus.

        $this->seed(ExistingCatalogProgramsSeeder::class);
        $this->assertDatabaseCount('programs', 70);
        $this->assertDatabaseCount('program_categories', 3);
        $this->assertDatabaseCount('university_partners', 5);
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

    public function test_existing_only_requires_the_actual_uws_university_partner_before_any_insert(): void
    {
        $this->fullCatalogReferences(includeUws: false);

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class,
            'Existing university partner required: university-of-the-west-of-scotland');

        $this->assertDatabaseCount('programs', 0);
        $this->assertDatabaseCount('program_categories', 3);
        $this->assertDatabaseCount('university_partners', 4);
    }

    public function test_repeat_seed_preserves_every_client_filled_draft_field_and_activation(): void
    {
        $this->fullCatalogReferences();
        $this->seed(ExistingCatalogProgramsSeeder::class);
        $slug = 'ba-global-business-level-9-university-of-the-west-of-scotland';
        $draft = Program::query()->where('slug', $slug)->firstOrFail();
        $id = $draft->id;
        $draft->update([
            'duration' => 'Confirmed by client',
            'short_description' => 'Client-reviewed summary',
            'description' => '<p>Client-reviewed copy.</p>',
            'highlights' => [['label' => 'Client', 'value' => 'Reviewed']],
            'structure' => [['title' => 'Client stage', 'modules' => [['title' => 'Confirmed module']]]],
            'fees' => [['title' => 'Client-confirmed fee']],
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 987,
        ]);
        $draft->seo()->firstOrFail()->update([
            'meta_title' => 'Client-approved SEO title',
            'meta_description' => 'Client-approved SEO description',
            'canonical_url' => 'https://example.test/client-reviewed',
        ]);
        $faq = $draft->faqs()->create([
            'question' => 'Client question?', 'answer' => '<p>Client answer.</p>', 'sort_order' => 1,
        ]);

        $this->seed(ExistingCatalogProgramsSeeder::class);

        $this->assertDatabaseCount('programs', 70);
        $this->assertSame($id, $draft->refresh()->id);
        $this->assertSame('Confirmed by client', $draft->duration);
        $this->assertSame('Client-reviewed summary', $draft->short_description);
        $this->assertSame('<p>Client-reviewed copy.</p>', $draft->description);
        $this->assertSame([['label' => 'Client', 'value' => 'Reviewed']], $draft->highlights);
        $this->assertSame([['title' => 'Client stage', 'modules' => [['title' => 'Confirmed module']]]], $draft->structure);
        $this->assertSame([['title' => 'Client-confirmed fee']], $draft->fees);
        $this->assertTrue($draft->is_active);
        $this->assertTrue($draft->is_featured);
        $this->assertSame(987, $draft->sort_order);
        $this->assertSame('Client-approved SEO title', $draft->seo->meta_title);
        $this->assertSame('Client-approved SEO description', $draft->seo->meta_description);
        $this->assertSame('https://example.test/client-reviewed', $draft->seo->canonical_url);
        $this->assertSame($faq->id, $draft->faqs()->firstOrFail()->id);
    }

    public function test_existing_accounting_award_under_another_slug_blocks_the_draft(): void
    {
        $this->fullCatalogReferences();
        $gau = UniversityPartner::query()->where('slug', 'girne-american-university')->firstOrFail();
        $category = ProgramCategory::query()->where('slug', 'bachelors')->firstOrFail();
        Program::query()->create([
            'title' => 'BSc Accounting',
            'slug' => 'bsc-accounting-girne-american-university',
            'program_category_id' => $category->id,
            'university_partner_id' => $gau->id,
        ]);

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class,
            'Possible existing Bachelor award conflicts with admin draft accounting-and-finance-management-girne-american-university: bsc-accounting-girne-american-university');
        $this->assertDatabaseCount('programs', 1);
    }

    public function test_existing_global_business_award_under_another_slug_blocks_the_draft(): void
    {
        $this->fullCatalogReferences();
        $uws = UniversityPartner::query()->where('slug', 'university-of-the-west-of-scotland')->firstOrFail();
        $category = ProgramCategory::query()->where('slug', 'bachelors')->firstOrFail();
        Program::query()->create([
            'title' => 'BA (Hons) in Global Business',
            'slug' => 'ba-hons-global-business-university-of-the-west-of-scotland',
            'program_category_id' => $category->id,
            'university_partner_id' => $uws->id,
        ]);

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class,
            'Possible existing Bachelor award conflicts with admin draft ba-global-business-level-9-university-of-the-west-of-scotland: ba-hons-global-business-university-of-the-west-of-scotland');
        $this->assertDatabaseCount('programs', 1);
    }

    public function test_existing_only_refuses_an_admin_draft_with_wrong_award_identity(): void
    {
        $this->fullCatalogReferences();
        $category = ProgramCategory::query()->where('slug', 'bachelors')->firstOrFail();
        $uws = UniversityPartner::query()->where('slug', 'university-of-the-west-of-scotland')->firstOrFail();
        Program::query()->create([
            'title' => 'BA (Hons) in Global Business',
            'slug' => 'ba-global-business-level-9-university-of-the-west-of-scotland',
            'level' => 'BA',
            'program_category_id' => $category->id,
            'university_partner_id' => $uws->id,
        ]);

        $this->assertSeedFails(ExistingCatalogProgramsSeeder::class,
            'Refusing to change existing programme identity: ba-global-business-level-9-university-of-the-west-of-scotland');
        $this->assertDatabaseCount('programs', 1);
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
        $uws = UniversityPartner::query()->where('slug', 'university-of-the-west-of-scotland')->firstOrFail();
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
        $this->assertDatabaseCount('programs', 74); // 67 pages + 3 drafts + 2 legacy + 2 untouched exceptions
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
        $this->assertDatabaseCount('university_partners', 5);
    }

    private function fullCatalogReferences(bool $includeUws = true): void
    {
        $this->bachelorReferences();
        foreach (['doctorate', 'diploma'] as $slug) {
            ProgramCategory::query()->create(['name' => ucfirst($slug), 'slug' => $slug]);
        }
        foreach (['rushford-business-school', 'gatehouse-awards', 'qualifi'] as $slug) {
            UniversityPartner::query()->create(['name' => $slug, 'slug' => $slug, 'country' => 'Example']);
        }
        if ($includeUws) {
            UniversityPartner::query()->create([
                'name' => 'University of the West of Scotland',
                'slug' => 'university-of-the-west-of-scotland',
                'country' => 'United Kingdom',
            ]);
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
