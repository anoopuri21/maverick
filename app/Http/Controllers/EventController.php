<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\FormMailer;
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

    /**
     * Display separate dedicated event registration page.
     */
    public function showRegister(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.event-register', [
            'event' => $event,
            'eventsSeo' => safe_settings(EventsSeoSettings::class),
        ]);
    }

    /**
     * Submit event registration form and send notification email via FormMailer (Zoho integration).
     */
    public function submitRegister(Request $request, string $slug, FormMailer $formMailer)
    {
        $event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'guest_count' => 'required|string|max:50',
            'attending_masterclass' => 'required|string|max:100',
        ];

        $labels = [
            'event_title' => 'Event Title',
            'event_date' => 'Event Date',
            'event_time' => 'Event Time',
            'venue' => 'Venue / Location',
            'name' => 'Full Name',
            'email' => 'Email Address',
            'phone' => 'Phone / Contact Number',
            'guest_count' => 'How many guests?',
            'attending_masterclass' => 'Joining Status',
        ];

        $customFields = $event->registration_fields ?? [];

        foreach ($customFields as $index => $field) {
            $key = "custom_{$index}";
            $isRequired = ! empty($field['is_required']);
            $rules[$key] = $isRequired ? 'required' : 'nullable';
            if (! empty($field['label'])) {
                $labels[$key] = $field['label'];
            }
        }

        $validated = $request->validate($rules);

        $formData = [
            'event_title' => $event->title,
            'event_date' => $event->event_date ? $event->event_date->format('l, d F Y') : 'N/A',
            'event_time' => $event->formatted_time ?: 'N/A',
            'venue' => $event->location ?: 'N/A',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'guest_count' => $validated['guest_count'],
            'attending_masterclass' => $validated['attending_masterclass'],
        ];

        foreach ($customFields as $index => $field) {
            $key = "custom_{$index}";
            if (isset($validated[$key])) {
                $formData[$key] = is_array($validated[$key]) ? implode(', ', $validated[$key]) : $validated[$key];
            }
        }

        $formMailer->send(
            $formData,
            "New Event Registration: {$event->title}",
            [
                'reply_to' => $validated['email'],
                'labels' => $labels,
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your registration has been submitted successfully.',
                'redirect_url' => route('thank-you'),
            ]);
        }

        return redirect()->route('thank-you')->with('success', 'Your registration has been submitted successfully.');
    }
}
