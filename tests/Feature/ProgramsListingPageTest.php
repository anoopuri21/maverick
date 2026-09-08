<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use App\Support\PublicContentCache;
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
        $response->assertSee('data-category="bachelors"', false);
        $this->assertMatchesRegularExpression(
            '/class="pl-card is-hidden"[^>]*data-category="bachelors"|data-category="bachelors"[^>]*class="pl-card is-hidden"/',
            $response->getContent()
        );
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
