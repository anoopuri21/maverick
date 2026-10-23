<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_listing_page_loads_successfully()
    {
        Event::create([
            'title' => 'Global Leadership Masterclass',
            'event_date' => now()->addDays(5)->toDateString(),
            'event_time' => '10:00 AM',
            'location' => 'London, UK',
            'description' => 'Join us for a global leadership summit.',
            'is_active' => true,
        ]);

        $response = $this->get(route('events'));

        $response->assertStatus(200);
        $response->assertSee('Global Leadership Masterclass');
        $response->assertSee('10:00 AM');
        $response->assertSee('London, UK');
    }

    public function test_events_ajax_infinite_load_returns_json()
    {
        for ($i = 1; $i <= 15; $i++) {
            Event::create([
                'title' => "Event Number $i",
                'event_date' => now()->subDays($i)->toDateString(),
                'is_active' => true,
            ]);
        }

        $response = $this->getJson(route('events', ['offset' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'has_more',
            'next_offset',
            'total',
        ]);

        $data = $response->json();
        $this->assertFalse($data['has_more']);
        $this->assertEquals(15, $data['next_offset']);
    }

    public function test_event_detail_page_loads_with_gallery()
    {
        $event = Event::create([
            'title' => 'Past Executive Forum 2025',
            'event_date' => now()->subMonth()->toDateString(),
            'event_time' => '02:00 PM',
            'location' => 'Dubai, UAE',
            'description' => 'A successful past executive networking event.',
            'display_image' => 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg',
            'gallery_images' => [
                'https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg',
                'https://images.pexels.com/photos/1181407/pexels-photo-1181407.jpeg',
            ],
            'is_active' => true,
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Past Executive Forum 2025');
        $response->assertSee('Dubai, UAE');
        $response->assertSee('Gallery');
        $response->assertSee('2 Photos');
        $response->assertSee('https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg');
    }
}
