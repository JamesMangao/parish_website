<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\MassSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::active()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Enhanced "Next Mass" logic: find the closest mass by searching through the week
        $now = now();
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        $nextMass = null;
        for ($i = 0; $i < 7; $i++) {
            $checkDayIndex = ($now->dayOfWeek + $i) % 7;
            $dayName = $daysOfWeek[$checkDayIndex];
            
            $masses = MassSchedule::where('is_active', true)
                ->where(function($q) use ($dayName) {
                    $q->whereJsonContains('day_of_week', $dayName);
                    
                    if ($dayName === 'Sunday') {
                        $q->orWhere('mass_type', 'sunday');
                    } elseif ($dayName === 'Saturday') {
                        $q->orWhere('mass_type', 'saturday')
                          ->orWhere('mass_type', 'anticipated');
                    }
                })
                ->get();
                
            $upcomingMassesToday = [];
            foreach ($masses as $mass) {
                $times = is_array($mass->time) ? $mass->time : (is_string($mass->time) ? json_decode($mass->time, true) : []);
                if (!$times) continue;

                foreach (array_filter($times) as $t) {
                    try {
                        if (str_contains($t, 'AM') || str_contains($t, 'PM')) {
                             $massTime = Carbon::createFromFormat('g:i A', trim($t));
                        } else {
                             // Handle H:i (e.g. 18:00)
                             $massTime = Carbon::createFromFormat('H:i', trim($t));
                        }
                        
                        // Set base date to today then add $i days
                        $massTime->setDate($now->year, $now->month, $now->day)->addDays($i);
                        $massEndTime = (clone $massTime)->addHour();
                        
                        // Scenario 1: Ongoing (Within 1 hour of start)
                        if ($now->between($massTime, $massEndTime)) {
                            $nextMass = clone $mass;
                            $nextMass->calculated_time = $massTime->format('g:i A');
                            $nextMass->calculated_day = 'Ongoing';
                            break 3; // Break out of all loops!
                        }
                        
                        // Scenario 2: Upcoming
                        if ($now->lessThan($massTime)) {
                            $m = clone $mass;
                            $m->calculated_time = $massTime->format('g:i A');
                            $m->calculated_day = ($i === 0) ? 'Today' : (($i === 1) ? 'Tomorrow' : $dayName);
                            // Store by timestamp to sort later
                            $upcomingMassesToday[$massTime->timestamp] = $m;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            
            if (!empty($upcomingMassesToday)) {
                ksort($upcomingMassesToday);
                $nextMass = reset($upcomingMassesToday);
                break;
            }
        }
        
        // Absolute fallback to first Sunday mass if nothing else matches
        if (!$nextMass) {
            $nextMass = MassSchedule::where('is_active', true)
                ->where('mass_type', 'sunday')
                ->orderByRaw('time->>0 asc')
                ->first();
            if ($nextMass) {
                $times = is_array($nextMass->time) ? $nextMass->time : (is_string($nextMass->time) ? json_decode($nextMass->time, true) : []);
                $fallbackTime = $times[0] ?? '6:00 AM';
                $nextMass->calculated_time = Carbon::parse($fallbackTime)->format('g:i A');
                $nextMass->calculated_day = 'Sunday';
            }
        }

        $upcomingEvents = \App\Models\Event::where('is_published', true)
            ->whereDate('event_date', '>=', $now->toDateString())
            ->orderBy('event_date', 'asc')
            ->take(2)
            ->get();

        return view('home', compact('announcements', 'nextMass', 'upcomingEvents'));
    }
}
