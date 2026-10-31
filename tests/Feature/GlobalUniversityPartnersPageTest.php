<?php

namespace Tests\Feature;

use App\Models\UniversityPartner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalUniversityPartnersPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_university_partners_page_renders_mlp_partners_immediately_after_the_university_map(): void
    {
        UniversityPartner::query()->create([
            'name' => 'Global Page Dynamic Partner',
            'logo_url' => 'https://cdn.example.com/global-page-partner.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/global-university-partners');

        $response->assertOk();
        $response->assertSee('assets/css/mba-partners.css', false);
        $response->assertSee('assets/js/archive-partners.js', false);
        $response->assertSee('Global Page Dynamic Partner', false);

        $content = $response->getContent();
        $universityPartnersStart = strpos($content, '<section id="university-partners"');
        $mlpPartnersStart = strpos($content, '<section class="mlp-partners archive-partners" id="mlp-partners"');

        $this->assertNotFalse($universityPartnersStart);
        $this->assertNotFalse($mlpPartnersStart);
        $this->assertSame(1, substr_count($content, 'id="mlp-partners"'));

        $universityPartnersEnd = strpos($content, '</section>', $universityPartnersStart);
        $this->assertNotFalse($universityPartnersEnd);

        $betweenSections = substr(
            $content,
            $universityPartnersEnd + strlen('</section>'),
            $mlpPartnersStart - ($universityPartnersEnd + strlen('</section>'))
        );
        $this->assertSame('', trim($betweenSections));
    }
}
