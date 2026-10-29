<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramOverviewUniversityCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_overview_card_uses_the_linked_university_instead_of_label_only_highlights(): void
    {
        $partner = UniversityPartner::query()->create([
            'name' => 'Northstar University',
            'country' => 'United Kingdom',
            'description' => '<p>Known for practical business education.</p><p>Its programmes connect students with industry.</p>',
            'is_active' => true,
        ]);

        $this->createProgram('label-only-highlights', $partner, [
            ['label' => 'Professional accreditation'],
        ]);

        $response = $this->get(route('programs.show', 'label-only-highlights'));

        $response->assertOk();
        $response->assertSee('Quick Highlights');
        $response->assertSee('Professional accreditation');
        $response->assertSee('data-testid="program-overview-university-card"', false);
        $response->assertSee('data-testid="program-overview-university-name">Northstar University</div>', false);
        $response->assertSee(
            'data-testid="program-overview-university-description">Known for practical business education. Its programmes connect students with industry.</div>',
            false
        );
        $response->assertDontSee('data-testid="program-overview-university-name">Leadership', false);
    }

    public function test_overview_card_is_rendered_for_a_linked_university_even_without_quick_highlights(): void
    {
        $partner = UniversityPartner::query()->create([
            'name' => 'Northstar University',
            'country' => 'United Kingdom',
            'description' => 'An internationally connected university.',
            'is_active' => true,
        ]);

        $this->createProgram('university-without-highlights', $partner, []);

        $response = $this->get(route('programs.show', 'university-without-highlights'));

        $response->assertOk();
        $response->assertSee('data-testid="program-overview-university-card"', false);
        $response->assertSee('data-testid="program-overview-university-name">Northstar University</div>', false);
        $response->assertSee('data-testid="program-overview-university-description">An internationally connected university.</div>', false);
        $response->assertDontSee('Quick Highlights');
    }

    public function test_overview_card_is_omitted_when_there_is_no_linked_university_partner(): void
    {
        $this->createProgram('no-university-partner', null, [
            ['label' => 'Flexible learning'],
        ]);

        $response = $this->get(route('programs.show', 'no-university-partner'));

        $response->assertOk();
        $response->assertSee('Flexible learning');
        $response->assertSee('ov-grid--single', false);
        $response->assertDontSee('data-testid="program-overview-university-card"', false);
        $response->assertDontSee('<div class="num">Leadership</div>', false);
    }

    private function createProgram(string $slug, ?UniversityPartner $partner, array $highlights): Program
    {
        $category = ProgramCategory::query()->create([
            'name' => 'MBA',
            'slug' => 'category-' . $slug,
            'is_active' => true,
        ]);

        return Program::query()->create([
            'program_category_id' => $category->id,
            'university_partner_id' => $partner?->id,
            'title' => 'Example MBA',
            'slug' => $slug,
            'description' => 'A programme overview for regression coverage.',
            'highlights' => $highlights,
            'is_active' => true,
        ]);
    }
}
