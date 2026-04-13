<?php

namespace App\Http\Controllers;

use App\Models\MassSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = MassSchedule::orderByRaw('CAST(day_of_week AS TEXT)')->orderByRaw('CAST(time AS TEXT)')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'mass_type' => 'required|string',
            'day_of_week' => 'nullable|array',
            'day_of_week.*' => 'string',
            'time' => 'required|array',
            'time.*' => 'string',
            'location' => 'nullable|string',
        ]);

        MassSchedule::create($validated);
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created.');
    }

    public function edit(MassSchedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, MassSchedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'mass_type' => 'required|string',
            'day_of_week' => 'nullable|array',
            'day_of_week.*' => 'string',
            'time' => 'required|array',
            'time.*' => 'string',
            'location' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $schedule->update($validated);
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated.');
    }

    public function destroy(MassSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Schedule deleted.');
    }
}
