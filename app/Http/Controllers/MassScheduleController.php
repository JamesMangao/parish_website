<?php

namespace App\Http\Controllers;

use App\Models\MassSchedule;
use Illuminate\Http\Request;

class MassScheduleController extends Controller
{
    public function index()
    {
        $schedules = MassSchedule::where('is_active', true)
            ->orderByRaw('time->>0 asc')
            ->get()
            ->groupBy('mass_type');

        return view('mass-schedule', compact('schedules'));
    }
}
