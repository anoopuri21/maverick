<?php

namespace Tests\Feature;

use App\Models\OurStoryTestimonial;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProgramsListingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_programs_index_renders_category_and_university_names(): void
    {
        $this->seedProgram();

        $response = $this->get('/programs');

        $response->assertOk();
        $response->assertSee('Global MBA', false);
        $response->assertSee('Masters', false);
        $response->assertSee('Example University', false);
        $response->assertSee('data-filter="masters"', false);
    }

    public function test_programs_index_renders_when_cache_has_camel_case_relation_keys(): void
    {
        $this->seedProgram();

        Cache::put(PublicContentCache::PROGRAMS_LISTING, [
            'categories' => [[
                'id' => 1,
                'name' => 'Masters',
                'slug' => 'masters',
                'icon' => null,
                'sort_order' => 0,
                'programs_count' => 1,
            ]],
            'programs' => [[
                'id' => 1,
                'title' => 'Global MBA',
                'slug' => 'global-mba',
                'duration' => '12 months',
                'level' => 'Masters',
                'short_description' => 'A sample programme.',
                'image_url' => null,
                'sort_order' => 0,
                'programCategory' => ['name' => 'Masters', 'slug' => 'masters'],
                'universityPartner' => ['name' => 'Example University'],
            ]],
        ], PublicContentCache::TTL);

        $response = $this->get('/programs');

        $response->assertOk();
        $response->assertSee('Global MBA', false);
        $response->assertSee('Masters', false);
        $response->assertSee('Example University', false);
        $this->assertStringNotContainsString('Attempt to read property', $response->getContent());
    }

    public function test_program_detail_renders_category_and_university_names(): void
    {
        $this->seedProgram();

        $response = $this->get('/programs/global-mba');

        $response->assertOk();
        $response->assertSee('Global MBA', false);
        $response->assertSee('Masters', false);
        $response->assertSee('Example University', false);
        $this->assertStringNotContainsString('Attempt to read property', $response->getContent());
    }

    public function test_program_detail_uses_page_assigned_shared_testimonials_and_preserves_success_stories(): void
    {
        $this->seedProgram();

        $program = Program::query()->where('slug', 'global-mba')->firstOrFail();
        $program->testimonials = [[
            'name' => 'Success Story Still Visible',
            'role' => 'MBA Graduate',
            'country' => 'India',
            'category' => 'Alumni',
        ]];
        $program->reviews = [[
            'name' => 'Legacy Google Review',
            'rating' => 5,
            'review' => 'This old review block must no longer render.',
        ]];
        $program->save();

        OurStoryTestimonial::create([
            'name' => 'Program Detail Testimonial',
            'position' => 'MBA Graduate',
            'organisation' => 'Example University',
            'testimonial' => 'This quote is assigned only to programme detail routes.',
            'pages' => [TestimonialPages::PROGRAM_DETAIL],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        OurStoryTestimonial::create([
            'name' => 'Our Story Only Testimonial',
            'testimonial' => 'This quote is not assigned to programme detail routes.',
            'pages' => [TestimonialPages::OUR_STORY],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $programResponse = $this->get(route('programs.show', 'global-mba'));

        $programResponse->assertOk();
        $programResponse->assertSee('id="mlp-testimonials"', false);
        $programResponse->assertSee('Program Detail Testimonial');
        $programResponse->assertSee('Success Story Still Visible');
        $programResponse->assertSee('aria-label="Student success stories"', false);
        $programResponse->assertDontSee('Our Story Only Testimonial');
        $programResponse->assertDontSee('Legacy Google Review');
        $programResponse->assertDontSee('aria-label="Student reviews"', false);

        $this->get(route('our-story'))
            ->assertOk()
            ->assertDontSee('Program Detail Testimonial');
    }

    public function test_navbar_view_all_links_include_category_query(): void
    {
        $this->seedProgram();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('category=masters', false);
        $response->assertSee('data-href="'.route('programs.index', ['category' => 'masters']).'"', false);
        $response->assertSee('View All Masters', false);
    }

    public function test_programs_index_activates_requested_category_filter(): void
    {
        $this->seedProgram();
        $this->seedSecondCategoryProgram();

        $response = $this->get('/programs?category=masters');

        $response->assertOk();
        $response->assertSee('data-filter="masters"', false);
        $this->assertMatchesRegularExpression(
            '/class="pl-filter__btn[^"]*is-active[^"]*"\s+data-filter="masters"/',
            $response->getContent()
        );
        $this->assertDoesNotMatchRegularExpression(
            '/class="pl-filter__btn[^"]*is-active[^"]*"\s+data-filter="all"/',
            $response->getContent()
        );
        $response->assertSee('data-filter="bachelors"', false);
        $response->assertSee('data-category="masters"', false);
        $response->assertDontSee('data-category="bachelors"', false);
        $response->assertDontSee('class="pl-card is-hidden"', false);
    }

    public function test_programs_index_paginates_only_programs_from_the_selected_category(): void
    {
        $this->seedProgram();
        $this->seedProgramsInCategory('Masters', 'masters', 'Additional Master', 24);
        $this->seedProgramsInCategory('Bachelors', 'bachelors', 'Bachelor Programme', 13);

        $firstPage = $this->get('/programs?category=bachelors');

        $firstPage->assertOk();
        $firstPageHtml = $firstPage->getContent();
        $this->assertSame(12, substr_count($firstPageHtml, 'class="pl-card"'));
        $this->assertStringContainsString('category=bachelors&amp;page=2', $firstPageHtml);
        $this->assertStringNotContainsString('category=bachelors&amp;page=3', $firstPageHtml);
        $this->assertStringNotContainsString('data-category="masters"', $firstPageHtml);

        $secondPage = $this->getJson('/programs?category=bachelors&page=2');

        $secondPage->assertOk();
        $secondPage->assertJsonPath('currentPage', 2);
        $secondPage->assertJsonPath('lastPage', 2);
        $secondPage->assertJsonPath('nextPage', null);
        $secondPage->assertJsonPath('total', 13);
        $secondPage->assertJsonPath('hasMorePages', false);
        $secondPageHtml = $secondPage->json('html');
        $this->assertSame(1, substr_count($secondPageHtml, 'class="pl-card"'));
        $this->assertStringContainsString('Bachelor Programme 13', $secondPageHtml);
        $this->assertStringNotContainsString('data-category="masters"', $secondPageHtml);
    }

    public function test_programs_index_defaults_to_all_filter(): void
    {
        $this->seedProgram();

        $response = $this->get('/programs');

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/class="pl-filter__btn[^"]*is-active[^"]*"\s+data-filter="all"/',
            $response->getContent()
        );
    }

    public function test_programs_index_falls_back_to_all_for_unknown_category(): void
    {
        $this->seedProgram();

        $response = $this->get('/programs?category=does-not-exist');

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/class="pl-filter__btn[^"]*is-active[^"]*"\s+data-filter="all"/',
            $response->getContent()
        );
    }

    private function seedProgram(): void
    {
        $category = ProgramCategory::query()->create([
            'name' => 'Masters',
            'slug' => 'masters',
            'is_active' => true,
        ]);

        $partner = UniversityPartner::query()->create([
            'name' => 'Example University',
            'country' => 'United Kingdom',
            'is_active' => true,
        ]);

        Program::query()->create([
            'program_category_id' => $category->id,
            'university_partner_id' => $partner->id,
            'title' => 'Global MBA',
            'slug' => 'global-mba',
            'duration' => '12 months',
            'level' => 'Masters',
            'short_description' => 'A sample programme.',
            'is_active' => true,
            'is_featured' => true,
        ]);
    }

    private function seedProgramsInCategory(string $categoryName, string $categorySlug, string $titlePrefix, int $count): void
    {
        $category = ProgramCategory::query()->firstOrCreate(
            ['slug' => $categorySlug],
            ['name' => $categoryName, 'is_active' => true]
        );

        $partner = UniversityPartner::query()->firstOrCreate(
            ['name' => 'Example University'],
            ['country' => 'United Kingdom', 'is_active' => true]
        );

        for ($i = 1; $i <= $count; $i++) {
            Program::query()->create([
                'program_category_id' => $category->id,
                'university_partner_id' => $partner->id,
                'title' => sprintf('%s %02d', $titlePrefix, $i),
                'slug' => sprintf('%s-pagination-%02d', $categorySlug, $i),
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
    }

    private function seedSecondCategoryProgram(): void
    {
        $category = ProgramCategory::query()->create([
            'name' => 'Bachelors',
            'slug' => 'bachelors',
            'is_active' => true,
        ]);

        $partner = UniversityPartner::query()->firstOrCreate(
            ['name' => 'Example University'],
            ['country' => 'United Kingdom', 'is_active' => true]
        );

        Program::query()->create([
            'program_category_id' => $category->id,
            'university_partner_id' => $partner->id,
            'title' => 'Global BBA',
            'slug' => 'global-bba',
            'duration' => '36 months',
            'level' => 'Undergraduate',
            'short_description' => 'A sample bachelor programme.',
            'is_active' => true,
        ]);
    }
}
