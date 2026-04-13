<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function publicIndex(Request $request)
    {
        $view = $request->get('view', 'list');
        
        $events = Event::where('is_published', true)
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        if ($view === 'calendar') {
            $month = $request->get('month', now()->month);
            $year = $request->get('year', now()->year);
            
            $calendarEvents = Event::where('is_published', true)
                ->whereMonth('event_date', $month)
                ->whereYear('event_date', $year)
                ->get();
                
            return view('events-calendar', [
                'events' => $calendarEvents,
                'month' => $month,
                'year' => $year,
            ]);
        }

        return view('events', compact('events'));
    }

    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required|array',
            'event_time.*.time' => 'nullable',
            'event_time.*.title' => 'nullable',
            'location' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['event_time'] = array_values(array_filter($validated['event_time'], function($item) {
            return !empty($item['time']) || !empty($item['title']);
        }));

        Event::create($validated);
        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required|array',
            'event_time.*.time' => 'nullable',
            'event_time.*.title' => 'nullable',
            'location' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['event_time'] = array_values(array_filter($validated['event_time'], function($item) {
            return !empty($item['time']) || !empty($item['title']);
        }));

        $event->update($validated);
        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }
}
