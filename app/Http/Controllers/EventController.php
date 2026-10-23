<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Settings\EventsPageSettings;
use App\Settings\EventsSeoSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    /**
     * Display event listing with separate Upcoming and Past events sections and pagination.
     */
    public function index(Request $request)
    {
        $limit = 10;
        $offset = (int) $request->query('offset', 0);
        $type = $request->query('type', 'upcoming');
        $today = Carbon::today()->toDateString();

        if ($request->ajax() || $request->wantsJson()) {
            $query = Event::query()->where('is_active', true);

            if ($type === 'past') {
                $query->whereDate('event_date', '<', $today)
                      ->orderBy('event_date', 'desc');
            } else {
                $query->whereDate('event_date', '>=', $today)
                      ->orderBy('event_date', 'asc');
            }

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

        $upcomingQuery = Event::query()
            ->where('is_active', true)
            ->whereDate('event_date', '>=', $today)
            ->orderBy('event_date', 'asc');

        $upcomingTotal = (clone $upcomingQuery)->count();
        $upcomingEvents = $upcomingQuery->take($limit)->get();

        $pastQuery = Event::query()
            ->where('is_active', true)
            ->whereDate('event_date', '<', $today)
            ->orderBy('event_date', 'desc');

        $pastTotal = (clone $pastQuery)->count();
        $pastEvents = $pastQuery->take($limit)->get();

        return view('pages.events', [
            'eventsPage' => safe_settings(EventsPageSettings::class),
            'upcomingEvents' => $upcomingEvents,
            'upcomingTotal' => $upcomingTotal,
            'upcomingHasMore' => $upcomingEvents->count() < $upcomingTotal,
            'pastEvents' => $pastEvents,
            'pastTotal' => $pastTotal,
            'pastHasMore' => $pastEvents->count() < $pastTotal,
            'eventsSeo' => safe_settings(EventsSeoSettings::class),
        ]);
    }

    /**
     * Display individual event detail page with More Events slider items.
     */
    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $moreEvents = Event::where('is_active', true)
            ->where('id', '!=', $event->id)
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        return view('pages.event-detail', [
            'event' => $event,
            'relatedEvents' => $moreEvents,
            'eventsSeo' => safe_settings(EventsSeoSettings::class),
        ]);
    }
}
