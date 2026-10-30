<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EdutainmentContentVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_intro_outline_cta_and_plain_text_quote_render_with_their_visible_markup(): void
    {
        $response = $this->get(route('edutainment'));

        $response->assertOk();
        $response->assertSee('id="edu-intro"', false);
        $response->assertSee('class="btn btn--outline">Speak to Our Team</a>', false);
        $response->assertSee('id="edu-what-is"', false);
        $response->assertSee(
            '<p>&ldquo;Learning becomes more memorable when students experience it for themselves.&rdquo;</p>',
            false,
        );
    }
}
