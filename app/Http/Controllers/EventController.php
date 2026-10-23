<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Settings\EventsPageSettings;
use App\Settings\EventsSeoSettings;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display event listing with initial batch and pagination support.
     */
    public function index(Request $request)
    {
        $limit = 10;
        $offset = (int) $request->query('offset', 0);

        $query = Event::query()
            ->where('is_active', true)
            ->orderBy('event_date', 'desc');

        if ($request->ajax() || $request->wantsJson()) {
            $total = (clone $query)->count();
            $events = $query->skip($offset)->take($limit)->get();

            $html = view('pages._event-cards', ['events' => $events])->render();
            $nextOffset = $offset + $events->count();
            $hasMore = $nextOffset < $total;

            return response()->json([
                'html' => $html,
                'has_more' => $hasMore,
                'next_offset' => $nextOffset,
                'total' => $total,
            ]);
        }

        $events = $query->take($limit)->get();
        $total = Event::query()->where('is_active', true)->count();

        return view('pages.events', [
            'eventsPage' => safe_settings(EventsPageSettings::class),
            'events' => $events,
            'total' => $total,
            'hasMore' => $events->count() < $total,
            'eventsSeo' => safe_settings(EventsSeoSettings::class),
        ]);
    }

    /**
     * Display individual event detail page.
     */
    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedEvents = Event::where('is_active', true)
            ->where('id', '!=', $event->id)
            ->orderBy('event_date', 'desc')
            ->take(3)
            ->get();

        return view('pages.event-detail', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            'eventsSeo' => safe_settings(EventsSeoSettings::class),
        ]);
    }
}
