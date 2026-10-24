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
            'event_type' => 'Masterclass',
            'event_date' => now()->addDays(5)->toDateString(),
            'event_start_time' => '10:00 AM',
            'event_end_time' => '01:00 PM',
            'location' => 'London, UK',
            'description' => 'Join us for a global leadership summit.',
            'is_active' => true,
        ]);

        $response = $this->get(route('events'));

        $response->assertStatus(200);
        $response->assertSee('Global Leadership Masterclass');
        $response->assertSee('Masterclass');
        $response->assertSee('10:00 AM - 01:00 PM');
        $response->assertSee('London, UK');
        $response->assertSee('Upcoming Events');
        $response->assertSee('Past Events');
    }

    public function test_events_ajax_infinite_load_returns_json()
    {
        for ($i = 1; $i <= 15; $i++) {
            Event::create([
                'title' => "Past Event Number $i",
                'event_date' => now()->subDays($i)->toDateString(),
                'is_active' => true,
            ]);
        }

        $response = $this->getJson(route('events', ['type' => 'past', 'offset' => 10]), [
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
        $this->assertEquals(15, $data['total']);
    }

    public function test_event_detail_page_loads_with_gallery_and_videos()
    {
        $event = Event::create([
            'title' => 'Past Executive Forum 2025',
            'event_type' => 'Forum',
            'event_date' => now()->subMonth()->toDateString(),
            'event_start_time' => '02:00 PM',
            'event_end_time' => '05:00 PM',
            'location' => 'Dubai, UAE',
            'description' => 'A successful past executive networking event.',
            'display_image' => 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg',
            'gallery_images' => [
                'https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg',
                'https://images.pexels.com/photos/1181407/pexels-photo-1181407.jpeg',
            ],
            'gallery_videos' => [
                ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'title' => 'Keynote Opening Session'],
            ],
            'is_active' => true,
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Past Executive Forum 2025');
        $response->assertSee('Forum');
        $response->assertSee('02:00 PM - 05:00 PM');
        $response->assertSee('Dubai, UAE');
        $response->assertSee('Photo');
        $response->assertSee('2 Photos');
        $response->assertSee('Video');
        $response->assertSee('Keynote Opening Session');
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ');
        $response->assertSee('More Events');
    }

    public function test_empty_fields_do_not_throw_errors_or_render_empty_sections()
    {
        $event = Event::create([
            'title' => 'Minimal Event',
            'event_date' => now()->addMonth()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Minimal Event');
        $response->assertDontSee('Photo Gallery');
        $response->assertDontSee('Video Gallery');
    }

    public function test_event_registration_page_loads_and_submits_successfully_with_checkboxes()
    {
        $event = Event::create([
            'title' => 'Generative AI 2.0 Masterclass',
            'event_date' => now()->addDays(10)->toDateString(),
            'event_start_time' => '10:00 AM',
            'event_end_time' => '02:00 PM',
            'location' => 'Grand Mercure Hotel, Dubai',
            'registration_fields' => [
                [
                    'label' => 'Company Name',
                    'type' => 'text',
                    'is_required' => true,
                ],
                [
                    'label' => 'Topics of Interest',
                    'type' => 'checkbox',
                    'options' => ['Generative AI', 'Prompt Engineering', 'LLM Fine-Tuning'],
                    'is_required' => true,
                ],
            ],
            'is_active' => true,
        ]);

        $getRes = $this->get(route('events.register', $event->slug));
        $getRes->assertStatus(200);
        $getRes->assertSee('Registration Form – Generative AI 2.0 Masterclass');
        $getRes->assertSee('Company Name');
        $getRes->assertSee('Topics of Interest');
        $getRes->assertSee('Prompt Engineering');

        $postRes = $this->post(route('events.register.submit', $event->slug), [
            'name' => 'Anoop Puri',
            'phone' => '+971500000000',
            'email' => 'puri.anoop21@gmail.com',
            'guest_count' => 'Single',
            'attending_masterclass' => "Yes, I'll be there",
            'custom_0' => 'Alutions FZE',
            'custom_1' => ['Generative AI', 'LLM Fine-Tuning'],
        ]);

        $postRes->assertRedirect(route('thank-you'));
    }
}
